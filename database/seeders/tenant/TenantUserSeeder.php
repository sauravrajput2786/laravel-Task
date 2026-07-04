<?php

declare(strict_types=1);

namespace Database\Seeders\Tenant;

use App\Models\Tenant\TenantUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Run once per tenant database by App\Console\Commands\TenantSeedCommand,
 * which is responsible for having already switched the "tenant"
 * connection to the correct database and for telling us which email
 * belongs here.
 */
final class TenantUserSeeder extends Seeder
{
    public function run(string $name, string $email): void
    {
        TenantUser::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('Password@123'),
                'email_verified_at' => now(),
            ],
        );
    }
}
