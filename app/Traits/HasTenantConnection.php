<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Applied to any Eloquent model that must always be queried against
 * whichever database is currently configured on the "tenant"
 * connection, rather than a fixed connection name.
 *
 * This is what makes a model "tenant-aware": TenantDatabaseService
 * rewrites config('database.connections.tenant') and reconnects it
 * per-request, and every model using this trait transparently follows
 * along.
 */
trait HasTenantConnection
{
    public function getConnectionName(): string
    {
        return 'tenant';
    }
}
