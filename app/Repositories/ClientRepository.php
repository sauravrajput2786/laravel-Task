<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ClientRepositoryInterface;
use App\Models\Client;
use Illuminate\Support\Collection;

final class ClientRepository implements ClientRepositoryInterface
{
    public function findByCode(string $clientCode): ?Client
    {
        return Client::query()
            ->where('client_code', $clientCode)
            ->first();
    }

    /**
     * @return Collection<int, Client>
     */
    public function all(): Collection
    {
        return Client::query()->orderBy('client_name')->get();
    }
}
