<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $account_id
 * @property string $scope_id
 * @property string $title
 *
 * @property User $user
 *
 * @scopes
 * @method static Builder whereScopeId(string $scopeId)
 */
class AmoInstance extends Model
{
    use HasFactory;

    protected $table = 'amo_instances';

    protected $fillable = [
        'account_id',
        'scope_id',
        'title',
    ];

    public $timestamps = false;

    public function scopeWhereScopeId(Builder $query, string $scopeId): Builder
    {
        return $query->where('scope_id', $scopeId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id', 'amojo_id');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'amo_access_token_id', 'id');
    }

    public static function findByScopeId(string $scopeId): AmoInstance
    {
        return self::whereScopeId($scopeId)->first();
    }

    public static function findByAccountId(string $scopeId): AmoInstance
    {
        return self::query()->where('account_id', $scopeId)->firstOrFail();
    }
}
