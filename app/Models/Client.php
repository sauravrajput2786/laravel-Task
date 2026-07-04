<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A row in the master database describing one tenant and how to reach
 * its dedicated physical database.
 *
 * @property int $id
 * @property string $client_name
 * @property string $client_code
 * @property string $db_server
 * @property int $db_port
 * @property string $db_name
 * @property string $db_user
 * @property string $db_password
 */
final class Client extends Model
{
    use HasFactory;

    /**
     * Always resolved against the "master" connection, regardless of
     * whatever the "tenant" connection currently points at.
     */
    protected $connection = 'master';

    protected $table = 'clients';

    protected $fillable = [
        'client_name',
        'client_code',
        'db_server',
        'db_port',
        'db_name',
        'db_user',
        'db_password',
    ];

    /**
     * Credentials are never serialized into API responses or debug dumps.
     */
    protected $hidden = [
        'db_user',
        'db_password',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Encrypted at rest (AES-256-CBC, tied to APP_KEY) and
            // transparently decrypted/encrypted by Eloquent.
            'db_password' => 'encrypted',
            'db_port' => 'integer',
        ];
    }

    /**
     * @return HasMany<ClientUser>
     */
    public function users(): HasMany
    {
        return $this->hasMany(ClientUser::class, 'client_code', 'client_code');
    }

    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }
}
