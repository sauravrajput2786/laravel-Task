<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Client;
use App\Models\Tenant\TenantUser;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserLoggedIn
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly TenantUser $user,
        public readonly Client $client,
        public readonly string $guard,
    ) {
    }
}
