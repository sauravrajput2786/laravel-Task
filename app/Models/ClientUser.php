<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Master-database lookup index mapping a user's email address to the
 * client_code that owns it. This is the piece that makes login scale:
 * a single indexed query on `client_users.email` tells us which of
 * the N tenant databases to connect to, without ever having to probe
 * tenant databases one by one.
 *
 * See README "Architecture Decisions -> Tenant Resolution Strategy"
 * for the full rationale versus the alternatives that were considered.
 *
 * @property int $id
 * @property string $email
 * @property string $client_code
 */
final class ClientUser extends Model
{
    protected $connection = 'master';

    protected $table = 'client_users';

    protected $fillable = [
        'email',
        'client_code',
    ];

    /**
     * @return BelongsTo<Client, ClientUser>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_code', 'client_code');
    }
}
