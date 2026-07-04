<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\TenantUserRepositoryInterface;
use App\Models\Tenant\TenantUser;

final class TenantUserRepository implements TenantUserRepositoryInterface
{
    public function findByEmail(string $email): ?TenantUser
    {
        return TenantUser::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): TenantUser
    {
        return TenantUser::query()->create($attributes);
    }
}
