<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $user_id
 * @property string $account_id
 * @property string $scope_id
 * @property string $title
 *
 * @property User $user
 */
class AmoConnection extends Model
{
    use HasFactory;

    protected $table = 'amo_connections';
    protected $fillable = [
        'user_id',
        'account_id',
        'scope_id',
        'title'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function findByScopeId(string $scopeId): AmoConnection
    {
        return self::query()->where('scope_id', $scopeId)->first();
    }
}
