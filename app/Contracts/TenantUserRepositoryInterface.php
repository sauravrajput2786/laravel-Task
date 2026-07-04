<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Tenant\TenantUser;

interface TenantUserRepositoryInterface
{
    /**
     * Look up a user by email within the currently active tenant
     * database connection. Caller is responsible for having already
     * switched to the correct tenant before calling this.
     */
    public function findByEmail(string $email): ?TenantUser;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): TenantUser;
}
