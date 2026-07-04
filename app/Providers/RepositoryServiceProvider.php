<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\ClientRepositoryInterface;
use App\Contracts\ClientUserRepositoryInterface;
use App\Contracts\TenantUserRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Repositories\ClientUserRepository;
use App\Repositories\TenantUserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Binds each repository contract to its Eloquent implementation. All
 * services and controllers depend on the interfaces, never the
 * concrete classes, so the persistence layer can be swapped (e.g. for
 * testing with in-memory fakes) without touching business logic.
 */
final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        ClientRepositoryInterface::class => ClientRepository::class,
        ClientUserRepositoryInterface::class => ClientUserRepository::class,
        TenantUserRepositoryInterface::class => TenantUserRepository::class,
    ];
}
