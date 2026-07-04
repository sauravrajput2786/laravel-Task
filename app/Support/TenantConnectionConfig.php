<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Client;

/**
 * Immutable value object describing how to connect to one tenant's
 * database. Wrapping the raw Client model attributes in a typed DTO
 * (rather than passing arrays around) keeps TenantDatabaseService
 * decoupled from the Eloquent model and makes the shape of a "tenant
 * connection" explicit and easy to unit test.
 */
final readonly class TenantConnectionConfig
{
    public function __construct(
        public string $clientCode,
        public string $host,
        public int $port,
        public string $database,
        public string $username,
        public string $password,
    ) {
    }

    public static function fromClient(Client $client): self
    {
        return new self(
            clientCode: $client->client_code,
            host: $client->db_server,
            port: $client->db_port,
            database: $client->db_name,
            username: $client->db_user,
            password: $client->db_password,
        );
    }

    /**
     * Shape expected by config('database.connections.tenant').
     *
     * @return array<string, mixed>
     */
    public function toLaravelConnectionArray(): array
    {
        return [
            'driver' => 'mysql',
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'username' => $this->username,
            'password' => $this->password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ];
    }
}
