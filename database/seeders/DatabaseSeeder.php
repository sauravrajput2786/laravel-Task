<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seeds the master database only. Tenant databases are seeded
     * separately via `php artisan tenants:seed` (see README), since
     * they require the "tenant" connection to be switched per client
     * first - something a single `db:seed` run against the default
     * connection cannot do on its own.
     */
    public function run(): void
    {
        $this->call([
            ClientSeeder::class,
        ]);
    }
}
