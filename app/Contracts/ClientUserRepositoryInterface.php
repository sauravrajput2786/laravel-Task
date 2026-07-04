<?php

declare(strict_types=1);

namespace App\Contracts;

interface ClientUserRepositoryInterface
{
    /**
     * Resolve the client_code that owns a given email address, or null
     * if the email is not registered against any tenant.
     */
    public function resolveClientCodeForEmail(string $email): ?string;

    /**
     * Register (or update) which tenant an email address belongs to.
     */
    public function map(string $email, string $clientCode): void;
}
