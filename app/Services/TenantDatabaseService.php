<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Client;
use App\Support\TenantConnectionConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Owns the runtime "tenant" database connection.
 *
 * Design notes:
 *  - We never write to .env or config files on disk. `Config::set()`
 *    only mutates the in-memory config repository for the lifetime of
 *    the current request/process.
 *  - `DB::purge('tenant')` closes and forgets any previously resolved
 *    PDO connection for the "tenant" name, so a stale connection from
 *    a *different* tenant (e.g. from a previous queued job iteration,
 *    or a leaked connection in long-running workers) is never reused.
 *  - `DB::reconnect('tenant')` then eagerly opens a fresh PDO
 *    connection using the just-updated config, so any connection
 *    errors (bad host/credentials) surface immediately rather than on
 *    the first query.
 */
final class TenantDatabaseService
{
    private ?Client $currentClient = null;

    /**
     * Point the "tenant" connection at the given client's database.
     */
    public function connect(Client $client): void
    {
        $config = TenantConnectionConfig::fromClient($client);

        Config::set('database.connections.tenant', $config->toLaravelConnectionArray());

        DB::purge('tenant');
        DB::reconnect('tenant');

        $this->currentClient = $client;
    }

    /**
     * The client the "tenant" connection is currently pointed at, if
     * connect() has been called during this request lifecycle.
     */
    public function currentClient(): ?Client
    {
        return $this->currentClient;
    }

    public function isConnected(): bool
    {
        return $this->currentClient !== null;
    }
}
