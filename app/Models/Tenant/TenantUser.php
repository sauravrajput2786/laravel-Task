<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use App\Traits\HasTenantConnection;
use Database\Factories\TenantUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * A user record inside one tenant's database (tenant_ibm, tenant_hcl,
 * or tenant_infosys). Identical schema across all three; which
 * physical database this queries against is decided per-request by
 * TenantDatabaseService, not by anything in this class.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 */
final class TenantUser extends Authenticatable
{
    use HasApiTokens, HasFactory, HasTenantConnection, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): TenantUserFactory
    {
        return TenantUserFactory::new();
    }
}
