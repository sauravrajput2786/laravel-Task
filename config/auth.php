<?php

declare(strict_types=1);

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'tenant_users',
    ],

    'guards' => [
        // Session-based guard used by the Blade login form and the
        // protected dashboard route. The provider resolves against
        // whichever tenant database is currently active on the
        // "tenant" connection (restored per-request from the session
        // by the `tenant.session` middleware).
        'web' => [
            'driver' => 'session',
            'provider' => 'tenant_users',
        ],

        // Stateless guard for the JSON API, backed by Sanctum personal
        // access tokens stored per-tenant.
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => 'tenant_users',
        ],
    ],

    'providers' => [
        'tenant_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Tenant\TenantUser::class,
        ],
    ],

    'passwords' => [
        'tenant_users' => [
            'provider' => 'tenant_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
