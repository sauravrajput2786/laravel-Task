<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\UserLoggedIn;
use App\Listeners\LogSuccessfulLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserLoggedIn::class => [
            LogSuccessfulLogin::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
