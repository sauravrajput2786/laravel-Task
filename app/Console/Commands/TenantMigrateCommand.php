<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\ClientRepositoryInterface;
use App\Services\TenantDatabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

final class TenantMigrateCommand extends Command
{
    /**
     * php artisan tenants:migrate
     * php artisan tenants:migrate --client=IBM
     * php artisan tenants:migrate --fresh
     */
    protected $signature = 'tenants:migrate
                            {--client= : Only migrate a single client_code}
                            {--fresh : Drop all tenant tables and re-migrate}';

    protected $description = 'Run the tenant migrations (database/migrations/tenant) against every client database registered in the master DB.';

    public function handle(
        ClientRepositoryInterface $clients,
        TenantDatabaseService $tenantDatabase,
    ): int {
        $allClients = $clients->all();

        if ($clientCode = $this->option('client')) {
            $allClients = $allClients->where('client_code', $clientCode);
        }

        if ($allClients->isEmpty()) {
            $this->warn('No matching clients found in the master database. Run `php artisan db:seed` first.');

            return self::FAILURE;
        }

        foreach ($allClients as $client) {
            $this->info("Migrating tenant database for [{$client->client_code}] ({$client->db_name})...");

            $tenantDatabase->connect($client);

            Artisan::call($this->option('fresh') ? 'migrate:fresh' : 'migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ], $this->getOutput());
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
