<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use App\Traits\HasTenantConnection;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * Sanctum tokens are stored per-tenant: each tenant database has its
 * own `personal_access_tokens` table, so a token minted while
 * connected to tenant_ibm can never resolve a user in tenant_hcl even
 * if the raw token string were somehow guessed or replayed.
 *
 * Registered via Sanctum::usePersonalAccessTokenModel() in
 * AppServiceProvider.
 */
final class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasTenantConnection;
}
