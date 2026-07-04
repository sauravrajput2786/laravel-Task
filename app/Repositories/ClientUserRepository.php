<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ClientUserRepositoryInterface;
use App\Models\ClientUser;

final class ClientUserRepository implements ClientUserRepositoryInterface
{
    public function resolveClientCodeForEmail(string $email): ?string
    {
        return ClientUser::query()
            ->where('email', $email)
            ->value('client_code');
    }

    public function map(string $email, string $clientCode): void
    {
        ClientUser::query()->updateOrCreate(
            ['email' => $email],
            ['client_code' => $clientCode],
        );
    }
}
