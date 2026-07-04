<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientUser;
use Illuminate\Database\Seeder;

final class ClientSeeder extends Seeder
{
    /**
     * Definitive list of tenants for this project, along with the demo
     * user each one owns. Adjust db_server/db_user/db_password to
     * match your local MySQL setup before running: php artisan db:seed
     */
    private const  CLIENTS = [
        [
            'client_name' => 'IBM',
            'client_code' => 'IBM',
            'db_server' => '127.0.0.1',
            'db_port' => 3306,
            'db_name' => 'tenant_ibm',
            'db_user' => 'root',
            'db_password' => '',
            'demo_email' => 'ibmuser@gmail.com',
        ],
        [
            'client_name' => 'HCL Technologies',
            'client_code' => 'HCL',
            'db_server' => '127.0.0.1',
            'db_port' => 3306,
            'db_name' => 'tenant_hcl',
            'db_user' => 'root',
            'db_password' => '',
            'demo_email' => 'hcluser@gmail.com',
        ],
        [
            'client_name' => 'Infosys',
            'client_code' => 'INFY',
            'db_server' => '127.0.0.1',
            'db_port' => 3306,
            'db_name' => 'tenant_infosys',
            'db_user' => 'root',
            'db_password' => '',
            'demo_email' => 'infyuser@gmail.com',
        ],
    ];

    public function run(): void
    {
        foreach (self::CLIENTS as $definition) {
            $demoEmail = $definition['demo_email'];
            unset($definition['demo_email']);

            $client = Client::query()->updateOrCreate(
                ['client_code' => $definition['client_code']],
                $definition,
            );

            ClientUser::query()->updateOrCreate(
                ['email' => $demoEmail],
                ['client_code' => $client->client_code],
            );

            $this->command?->info("Registered client [{$client->client_code}] -> {$demoEmail}");
        }
    }

    /**
     * Exposes the client_code => demo email map so the tenant seeder
     * (which runs against each tenant DB individually) can create the
     * matching user without duplicating this list.
     *
     * @return array<string, string>
     */
    public static function demoEmailMap(): array
    {
        return collect(self::CLIENTS)->pluck('demo_email', 'client_code')->all();
    }
}
