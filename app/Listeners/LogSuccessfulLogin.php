<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Log;

/**
 * Writes an audit-trail log line for every successful login, tagged
 * with the tenant it happened against. In a real deployment this is a
 * natural extension point for shipping to a SIEM / audit log store.
 */
final class LogSuccessfulLogin
{
    public function handle(UserLoggedIn $event): void
    {
        Log::info('tenant.login.success', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'client_code' => $event->client->client_code,
            'guard' => $event->guard,
        ]);
    }
}
