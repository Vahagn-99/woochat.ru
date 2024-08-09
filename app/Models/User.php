<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $amojo_id
 * @property string $domain
 * @property string $email
 * @property string $phone
 * @property string $password
 *
 * @property-read \App\Models\AmoAccessToken $amoAccessToken
 */
final class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'domain',
        'email',
        'phone',
        'amojo_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booting(): void
    {
        User::creating(function (User $user) {
            $user->password = Hash::make($user->password ?? 'password');
        });
    }

    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class, 'user_id', 'id');
    }

    public function amoConnections(): HasMany
    {
        return $this->hasMany(AmoConnection::class, 'user_id', 'id');
    }

    public function amoAccessToken(): HasOne
    {
        return $this->hasOne(AmoAccessToken::class, 'domain', 'domain');
    }

    public static function findByDomain(mixed $domain): ?User
    {
        return User::query()->where('domain', $domain)->first();
    }
}
