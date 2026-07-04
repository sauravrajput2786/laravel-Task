<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tenant\TenantUser;

/**
 * This SaaS has a single per-user resource (their own dashboard/
 * profile), so the only authorization rule that matters is "a user
 * may only view their own record" - still expressed as a policy
 * rather than an inline check so the rule lives in one place and is
 * easy to extend as more tenant-scoped resources are added.
 */
final class TenantUserPolicy
{
    public function view(TenantUser $authUser, TenantUser $target): bool
    {
        return $authUser->id === $target->id;
    }
}
