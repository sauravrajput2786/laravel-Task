<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Client;

interface ClientRepositoryInterface
{
    /**
     * Find an active client by its unique client_code.
     */
    public function findByCode(string $clientCode): ?Client;

    /**
     * @return \Illuminate\Support\Collection<int, Client>
     */
    public function all(): \Illuminate\Support\Collection;
}
