# Tenant SaaS — Multi-Tenant Authentication System

A production-oriented, database-per-tenant authentication system built
on Laravel 12 + Sanctum, with both a Blade/jQuery web login and a JSON
API. One master database holds the tenant registry; each tenant
(`IBM`, `HCL`, `Infosys`) has its own physical database and its own
`users` table.

---

## 1. Architecture decisions

### 1.1 Tenant resolution strategy — why a `client_users` index table

The spec asks: given only an email address, how do we find which
tenant owns it, **without** scanning every tenant database? Three
approaches were considered:

| Approach | Verdict |
|---|---|
| **Scan every tenant DB for the email** | Rejected. Cost grows linearly with tenant count (O(n) connection attempts per login), leaks timing information about which tenants exist, and requires holding credentials for every tenant open simultaneously just to answer one login request. |
| **Store an `email` column directly on `clients`** | Rejected. A client can have many users, so this either duplicates client rows per user (denormalized, breaks the 1-row-per-tenant model the `clients` table is for) or forces a comma-separated/JSON email list (unindexable, doesn't scale, painful to update). |
| **A dedicated `client_users` mapping table (email → client_code), separate from `clients`** | **Chosen.** A single `UNIQUE` index on `email` makes tenant resolution an O(log n) indexed lookup regardless of tenant count or users-per-tenant. It cleanly separates "which tenant exists and how to connect to it" (`clients`) from "who belongs to which tenant" (`client_users`), and scales the same way at 3 tenants or 3,000. |

This is the enterprise-standard shape: an identity/directory index in
the control-plane database, with the actual user records living in
each tenant's own database.

### 1.2 Runtime database switching

`TenantDatabaseService` never touches `.env` or writes config files.
It rewrites `config('database.connections.tenant')` in memory via
`Config::set()`, then calls `DB::purge('tenant')` (drop any previously
opened connection for that name) followed by `DB::reconnect('tenant')`
(open a fresh one immediately, so bad credentials fail loudly right
away rather than on the first query). Every tenant-scoped Eloquent
model (`TenantUser`, `PersonalAccessToken`) uses the `HasTenantConnection`
trait to always resolve against this connection.

### 1.3 Session vs. API tenant context

- **Web (session) flow:** after a successful login, the resolved
  `client_code` is stored in the session. On every subsequent request,
  `ResolveTenantFromSession` middleware reads it back and reconnects
  the tenant DB *before* the `auth` middleware tries to load the user.
- **API (stateless) flow:** a bearer token alone can't tell us which
  of the three physical databases to check it against, so authenticated
  API calls must also send an `X-Client-Code` header (returned at
  login). `ResolveTenantFromHeader` middleware resolves it before
  `auth:sanctum` runs.
- Sanctum tokens themselves are stored **per-tenant** — each tenant
  database has its own `personal_access_tokens` table — so a token
  minted while connected to `tenant_ibm` cannot resolve a user in
  `tenant_hcl` even in principle.

### 1.4 Layered architecture

```
Controller (thin, HTTP only)
    -> Service (business logic: AuthenticationService, TenantResolverService, TenantDatabaseService)
        -> Repository (data access, behind an interface: ClientRepository, ClientUserRepository, TenantUserRepository)
            -> Eloquent Model
```

Controllers contain no business logic — they translate HTTP in,
delegate to a service, translate the result back to HTTP. Services
depend on repository **interfaces** (`app/Contracts`), not concrete
Eloquent repositories, so the persistence layer is swappable (e.g. for
testing with fakes) without touching business logic. This is bound in
`app/Providers/RepositoryServiceProvider.php`.

---

## 2. Requirements

* PHP >= 8.3, Composer
* MySQL >= 5.7 (or MariaDB)
* Laravel 12 / Sanctum 4 (declared in `composer.json`)

---

## 3. Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

`APP_KEY` must exist **before** creating any `clients` rows, since
`clients.db_password` is encrypted using it.

### 3.1 Master database

Edit `.env`:

```env
DB_CONNECTION=master
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=master_db
DB_USERNAME=root
DB_PASSWORD=
```

```sql
CREATE DATABASE master_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.2 Tenant databases

```sql
CREATE DATABASE tenant_ibm     CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE tenant_hcl     CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE tenant_infosys CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

(Raw SQL equivalents of every migration live in `database/sql/` if you
prefer not to use `artisan migrate`.)

### 3.3 Migrate + seed

```bash
php artisan migrate          # creates clients + client_users in master_db
php artisan db:seed          # registers IBM / HCL / Infosys + their demo emails
php artisan tenants:migrate  # creates users/personal_access_tokens in each tenant DB
php artisan tenants:seed     # creates the demo user inside each tenant DB
```

Useful flags: `--client=IBM` (single tenant only), `--fresh` (drop &
re-create tenant tables, on `tenants:migrate`).

### 3.4 Serve

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000/login`.

---

## 4. Demo accounts

| Client | Email | Password |
|---|---|---|
| IBM | ibmuser@gmail.com | Password@123 |
| HCL | hcluser@gmail.com | Password@123 |
| Infosys | infyuser@gmail.com | Password@123 |

---

## 5. Web login flow

1. `GET /login` — Blade form.
2. `POST /login` — `LoginController::store` calls
   `AuthenticationService::loginForWeb()`, which:
   resolves the tenant from the email via `client_users` → connects the
   tenant DB → `Auth::guard('web')->attempt()` (Laravel's standard,
   hashed credential check) → regenerates the session ID → stores
   `client_code` in session → issues a Sanctum token (shown once on the
   dashboard) → dispatches `UserLoggedIn`.
3. `GET /dashboard` (protected by `tenant.session` + `auth`) shows the
   user's name, email, tenant/client code, tenant database name, and
   the one-time API token.
4. `POST /logout` revokes the Sanctum token, logs out of the guard,
   invalidates the session, and rotates the CSRF token.

---

## 6. JSON API

### 6.1 Login

```
POST /api/login
Content-Type: application/json

{ "email": "ibmuser@gmail.com", "password": "Password@123" }
```

```json
{
  "status": true,
  "token": "1|abcdef123456...",
  "token_type": "Bearer",
  "client_code": "IBM",
  "user": { "id": 1, "name": "IBM Demo User", "email": "ibmuser@gmail.com" }
}
```

### 6.2 Authenticated requests

Every call after login needs **both**:

* `Authorization: Bearer <token>`
* `X-Client-Code: IBM`

```
GET /api/user
Authorization: Bearer 1|abcdef123456...
X-Client-Code: IBM
```

```json
{ "status": true, "client_code": "IBM", "user": { "id": 1, "name": "IBM Demo User", "email": "ibmuser@gmail.com" } }
```

### 6.3 Logout

```
POST /api/logout
Authorization: Bearer 1|abcdef123456...
X-Client-Code: IBM
```

---

## 7. Folder structure

```
app/
  Console/Commands/
    TenantMigrateCommand.php     # php artisan tenants:migrate
    TenantSeedCommand.php        # php artisan tenants:seed
  Contracts/                     # repository interfaces
    ClientRepositoryInterface.php
    ClientUserRepositoryInterface.php
    TenantUserRepositoryInterface.php
  Events/UserLoggedIn.php
  Exceptions/
    TenantNotFoundException.php
    InvalidTenantCredentialsException.php
  Helpers/tenant_helpers.php     # current_tenant(), tenant_database_name()
  Http/
    Controllers/
      Auth/LoginController.php   # web login/logout
      Api/AuthController.php     # api login/user/logout
      DashboardController.php
    Middleware/
      ResolveTenantFromSession.php
      ResolveTenantFromHeader.php
    Requests/Auth/LoginRequest.php
  Listeners/LogSuccessfulLogin.php
  Models/
    Client.php                   # master connection
    ClientUser.php                # master connection (email -> client_code)
    Tenant/TenantUser.php         # dynamic "tenant" connection
    Tenant/PersonalAccessToken.php
  Policies/TenantUserPolicy.php
  Providers/
    AppServiceProvider.php        # Sanctum token model + policy registration
    RepositoryServiceProvider.php # interface -> implementation bindings
    EventServiceProvider.php
  Repositories/                  # Eloquent implementations of the Contracts
  Rules/StrongPassword.php
  Services/
    TenantDatabaseService.php     # the core connection-switching logic
    TenantResolverService.php     # email -> client_code -> connect
    AuthenticationService.php     # login/logout orchestration
  Support/TenantConnectionConfig.php  # DTO for a tenant's DB credentials
  Traits/HasTenantConnection.php

bootstrap/
  app.php                        # routing, middleware aliases, exception rendering
  providers.php

database/
  migrations/                    # master DB migrations
  migrations/tenant/             # tenant DB migrations
  seeders/ClientSeeder.php       # registers the 3 clients + demo emails
  seeders/tenant/TenantUserSeeder.php
  factories/
  sql/                           # raw SQL equivalents + sample data

resources/views/
  layouts/app.blade.php
  auth/login.blade.php
  dashboard.blade.php
  components/alert.blade.php
  errors/{404,403,500}.blade.php

public/css/style.css             # plain CSS, no framework
public/js/app.js                 # jQuery helpers (toasts, CSRF header, validation)

routes/{web,api,console}.php
```

> **Laravel 12 note:** this project uses the current Laravel skeleton
> (no `app/Http/Kernel.php`, `app/Console/Kernel.php`, or
> `app/Exceptions/Handler.php`) — middleware, routing, and exception
> rendering are all configured in `bootstrap/app.php`.

---

## 8. Security

* **Encrypted credentials at rest:** `clients.db_password` uses
  Laravel's `encrypted` Eloquent cast (AES-256-CBC, tied to `APP_KEY`)
  and is excluded from serialization via `$hidden`.
* **Password hashing:** bcrypt via Eloquent's `hashed` cast; verified
  through Laravel's own `Auth::attempt()`, never manual string
  comparison.
* **CSRF protection:** enabled by default on all web routes; the
  Sanctum stateful middleware protects the SPA-style cookie flow if
  used; `app.js` attaches the CSRF meta tag to all AJAX requests.
* **Rate limiting:** `/login` and `/api/login` are throttled to 5
  attempts/minute per IP.
* **Session hardening:** session ID is regenerated on every successful
  login (fixation protection); full invalidation + CSRF token rotation
  on logout.
* **Per-tenant token isolation:** Sanctum tokens live in each tenant's
  own `personal_access_tokens` table — there is no shared token store
  a cross-tenant lookup could hit.
* **Generic auth error messages:** "email not found" and "wrong
  password" are surfaced identically (`InvalidTenantCredentialsException`
  and `TenantNotFoundException` render the same generic text) to avoid
  user enumeration.
* **No leaked internals:** `bootstrap/app.php` renders branded
  `errors/404.blade.php` / `403.blade.php` / `500.blade.php` views
  instead of Laravel's debug page whenever `APP_DEBUG=false`.
* **Mass-assignment protection:** every model declares an explicit
  `$fillable` allow-list.
* **Validation:** `LoginRequest` enforces required/email-format rules;
  `App\Rules\StrongPassword` is available for any future flow that
  *sets* a password (registration, password reset) — deliberately not
  applied to the login form itself, since validating password
  complexity at login time would let an attacker distinguish "wrong
  password" from "right password, fails policy" and narrow a
  credential-stuffing attack.

---

## 9. Extending this project

* **More tenants:** insert a new row into `clients` (and a matching
  `client_users` row per user), then run
  `php artisan tenants:migrate --client=NEW_CODE && php artisan tenants:seed --client=NEW_CODE`.
* **Password reset / registration:** wire up
  `Illuminate\Auth\Passwords\PasswordBroker` against the
  `tenant_users` provider already configured in `config/auth.php`, and
  apply `App\Rules\StrongPassword` on the new-password field.
* **Full OAuth2 (scopes, refresh tokens, client-credentials grant):**
  swap Sanctum for `laravel/passport`. `TenantDatabaseService` and the
  two tenant-resolution middleware classes are reusable as-is, since
  they operate purely at the database-connection level and know
  nothing about which token implementation sits on top.
