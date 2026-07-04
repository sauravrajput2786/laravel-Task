<?php

declare(strict_types=1);

use App\Models\Client;
use App\Services\TenantDatabaseService;

if (! function_exists('current_tenant')) {
    /**
     * Convenience accessor for the Client the "tenant" connection is
     * currently pointed at during this request, or null if no tenant
     * has been resolved yet. Used heavily in Blade views (dashboard)
     * so they don't need the service injected directly.
     */
    function current_tenant(): ?Client
    {
        return app(TenantDatabaseService::class)->currentClient();
    }
}

if (! function_exists('tenant_database_name')) {
    /**
     * Shortcut for displaying the physical database name of the
     * currently active tenant, e.g. on the dashboard.
     */
    function tenant_database_name(): ?string
    {
        return current_tenant()?->db_name;
    }
}
