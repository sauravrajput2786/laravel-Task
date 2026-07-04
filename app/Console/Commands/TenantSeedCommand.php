<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\ClientRepositoryInterface;
use App\Services\TenantDatabaseService;
use Database\Seeders\ClientSeeder;
use Database\Seeders\Tenant\TenantUserSeeder;
use Illuminate\Console\Command;

final class TenantSeedCommand extends Command
{
    /**
     * php artisan tenants:seed
     * php artisan tenants:seed --client=IBM
     */
    protected $signature = 'tenants:seed {--client= : Only seed a single client_code}';

    protected $description = 'Seed the demo user into each tenant database, matching the email registered in client_users.';

    public function handle(
        ClientRepositoryInterface $clients,
        TenantDatabaseService $tenantDatabase,
        TenantUserSeeder $tenantUserSeeder,
    ): int {
        $demoEmails = ClientSeeder::demoEmailMap();

        $allClients = $clients->all();

        if ($clientCode = $this->option('client')) {
            $allClients = $allClients->where('client_code', $clientCode);
        }

        foreach ($allClients as $client) {
            $email = $demoEmails[$client->client_code] ?? null;

            if ($email === null) {
                $this->warn("No demo email mapping for [{$client->client_code}], skipping.");

                continue;
            }

            $tenantDatabase->connect($client);

            $tenantUserSeeder->run("{$client->client_name} Demo User", $email);

            $this->info("Seeded {$email} into [{$client->client_code}] ({$client->db_name}).");
        }

        return self::SUCCESS;
    }
}
