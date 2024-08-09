<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * @property string $domain
 * @property string $access_token
 * @property string $refresh_token
 * @property string $expires
 * @property string $created_at
 *
 * @property-read User $user
 *
 */
class AmoAccessToken extends Model
{
    use HasFactory;

    public $incrementing = true;

    protected $table = "amo_access_tokens";

    protected $fillable = [
        "domain",
        "access_token",
        "refresh_token",
        "expires",
    ];

    public $timestamps = false;

    public static function findByDomain(string $domain): AmoAccessToken
    {
        return AmoAccessToken::query()->where("domain", $domain)->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'domain', 'domain');
    }

    public function getAccessToken(): AccessToken
    {
        return new AccessToken([
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token,
            'expires' => $this->expires,
        ]);
    }

    public static function saveWithDomain(string $domain, AccessTokenInterface $token): AmoAccessToken
    {
        return AmoAccessToken::query()->updateOrCreate(['domain' => $domain], [
            'access_token' => $token->getToken(),
            'refresh_token' => $token->getRefreshToken(),
            'expires' => $token->getExpires(),
        ]);
    }
}
