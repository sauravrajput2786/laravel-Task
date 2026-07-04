<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\UserLoggedIn;
use App\Exceptions\InvalidTenantCredentialsException;
use App\Models\Client;
use App\Models\Tenant\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Orchestrates the full login/logout flow described in the project
 * spec. Deliberately holds no HTTP concerns (no Request/Response
 * manipulation beyond session storage) so it can be reused
 * identically from both the web controller and the API controller -
 * controllers stay thin and only translate between HTTP and this
 * service.
 */
final readonly class AuthenticationService
{
private const SESSION_TENANT_KEY = 'tenant.client_code';
private const SESSION_TOKEN_FLASH_KEY = 'tenant.flash_api_token';

    public function __construct(
        private TenantResolverService $tenantResolver,
    ) {
    }

    /**
     * Full session-based login flow (steps 3-9 of the spec):
     * resolve tenant -> connect -> authenticate -> regenerate session
     * -> persist tenant context in session -> dispatch login event.
     *
     * @throws \App\Exceptions\TenantNotFoundException
     * @throws InvalidTenantCredentialsException
     */
    public function loginForWeb(Request $request, string $email, string $password): Client
    {
        $client = $this->tenantResolver->resolveAndConnect($email);

        if (! Auth::guard('web')->attempt(['email' => $email, 'password' => $password])) {
            throw InvalidTenantCredentialsException::forEmail($email);
        }

        // Prevent session fixation by rotating the session ID on every
        // successful authentication.
        $request->session()->regenerate();

        Session::put(self::SESSION_TENANT_KEY, $client->client_code);

        /** @var TenantUser $user */
        $user = Auth::guard('web')->user();

        // Also issue a Sanctum API token so the dashboard can display
        // it once (plain-text tokens cannot be retrieved again after
        // creation, per Sanctum's design) for anyone who wants to call
        // the JSON API afterwards.
        $plainTextToken = $user->createToken('web-session-token')->plainTextToken;
        Session::flash(self::SESSION_TOKEN_FLASH_KEY, $plainTextToken);

        UserLoggedIn::dispatch($user, $client, 'web');

        return $client;
    }

    /**
     * Stateless API login: resolve tenant -> connect -> verify
     * credentials -> issue a Sanctum personal access token scoped to
     * that tenant's own token table.
     *
     * @return array{client: Client, user: TenantUser, token: string}
     *
     * @throws \App\Exceptions\TenantNotFoundException
     * @throws InvalidTenantCredentialsException
     */
    public function loginForApi(string $email, string $password): array
    {
        $client = $this->tenantResolver->resolveAndConnect($email);

        if (! Auth::guard('web')->attempt(['email' => $email, 'password' => $password])) {
            throw InvalidTenantCredentialsException::forEmail($email);
        }

        /** @var TenantUser $user */
        $user = Auth::guard('web')->user();

        // We only used the "web" guard to run the standard Laravel
        // credential check (hashing/verification via Eloquent); we do
        // not want a server-side session for a stateless API client.
        Auth::guard('web')->logout();

        $token = $user->createToken('api-token')->plainTextToken;

        UserLoggedIn::dispatch($user, $client, 'api');

        return ['client' => $client, 'user' => $user, 'token' => $token];
    }

    /**
     * Full logout flow (spec step "LOGOUT"): destroy the Sanctum
     * token if present, log out of the web guard, invalidate the
     * session, forget the tenant context, and rotate the CSRF token.
     */
    public function logout(Request $request): void
    {
        $user = $request->user();

        if ($user instanceof TenantUser && $user->currentAccessToken() !== null) {
            $user->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();

        Session::forget(self::SESSION_TENANT_KEY);

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }

    public static function sessionTenantKey(): string
    {
        return self::SESSION_TENANT_KEY;
    }

    public static function sessionTokenFlashKey(): string
    {
        return self::SESSION_TOKEN_FLASH_KEY;
    }
}
