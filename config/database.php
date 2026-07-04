<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | "master" is the always-on connection holding the tenant registry
    | (`clients`) and the email->tenant lookup index (`client_users`).
    |
    | "tenant" is a placeholder connection whose driver options are
    | rewritten and reconnected at runtime by
    | App\Services\TenantDatabaseService once a client has been resolved.
    | It must never be queried with these default (null) values.
    |
    */

    'default' => env('DB_CONNECTION', 'master'),

    'connections' => [

        'master' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'master_db'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        'tenant' => [
            'driver' => 'mysql',
            'host' => null,
            'port' => null,
            'database' => null,
            'username' => null,
            'password' => null,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

];
