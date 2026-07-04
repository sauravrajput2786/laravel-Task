<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ClientRepositoryInterface;
use App\Contracts\ClientUserRepositoryInterface;
use App\Exceptions\TenantNotFoundException;
use App\Models\Client;

/**
 * Determines which tenant a given email address belongs to, and
 * switches the runtime "tenant" database connection to point at it.
 *
 * Resolution strategy: a single indexed lookup against the master
 * database's `client_users` table (email -> client_code), followed by
 * a lookup of the client's connection details in `clients`. See the
 * README for why this beats scanning every tenant database or storing
 * emails directly on the `clients` table.
 */
final readonly class TenantResolverService
{
    public function __construct(
        private ClientUserRepositoryInterface $clientUsers,
        private ClientRepositoryInterface $clients,
        private TenantDatabaseService $tenantDatabase,
    ) {
    }

    /**
     * Resolve the tenant owning $email, connect the "tenant" database
     * connection to it, and return the resolved Client.
     *
     * @throws TenantNotFoundException
     */
    public function resolveAndConnect(string $email): Client
    {
        $clientCode = $this->clientUsers->resolveClientCodeForEmail($email);

        if ($clientCode === null) {
            throw TenantNotFoundException::forEmail($email);
        }

        $client = $this->clients->findByCode($clientCode);

        if ($client === null) {
            throw TenantNotFoundException::forClientCode($clientCode);
        }

        $this->tenantDatabase->connect($client);

        return $client;
    }

    /**
     * Connect directly by client_code, bypassing email lookup. Used
     * when the caller already knows the tenant (e.g. restoring a
     * session, or an API request carrying X-Client-Code).
     *
     * @throws TenantNotFoundException
     */
    public function connectByClientCode(string $clientCode): Client
    {
        $client = $this->clients->findByCode($clientCode);

        if ($client === null) {
            throw TenantNotFoundException::forClientCode($clientCode);
        }

        $this->tenantDatabase->connect($client);

        return $client;
    }
}
