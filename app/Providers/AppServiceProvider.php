<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Tenant\PersonalAccessToken;
use App\Models\Tenant\TenantUser;
use App\Policies\TenantUserPolicy;
use App\Services\TenantDatabaseService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Must be a singleton: the same instance has to be shared by
        // the tenant-resolution middleware (which calls connect())
        // and every other consumer (helpers, controllers) that reads
        // currentClient() later in the same request. Without this,
        // each app(TenantDatabaseService::class) call resolves a
        // fresh instance with no client set.
        $this->app->singleton(TenantDatabaseService::class);
    }

    public function boot(): void
    {
        // WAMP/older MySQL & MariaDB default to a row format that caps
        // index keys at 767 bytes. utf8mb4 uses up to 4 bytes/char, so
        // a default VARCHAR(255) unique index (255*4=1020 bytes)
        // exceeds that. Capping the default string length to 191
        // (191*4=764 bytes) keeps every unique/index column under the
        // limit without touching individual migrations.
        Schema::defaultStringLength(191);

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Gate::policy(TenantUser::class, TenantUserPolicy::class);
    }
}
