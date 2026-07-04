<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
final class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = $this->faker->unique()->lexify('client???');

        return [
            'client_name' => $this->faker->company(),
            'client_code' => strtoupper($code),
            'db_server' => '127.0.0.1',
            'db_port' => 3306,
            'db_name' => 'tenant_'.strtolower($code),
            'db_user' => 'root',
            'db_password' => '',
        ];
    }
}
