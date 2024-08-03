<?php

namespace App\Models;

use App\Enums\InstanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property int $user_id
 * @property string $name
 * @property InstanceStatus $status
 * @property string $token
 * @property string $phone
 */
final class Instance extends Model
{
    use HasFactory;

    protected $table = 'instances';
    protected $fillable = [
        'id',
        'name',
        'user_id',
        'status',
        'token',
        'phone'
    ];

    public $incrementing = false;
    public $timestamps = false;
    protected $casts = [
        'status' => InstanceStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(Settings::class, 'instance_id', 'id');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'instance_id', 'id');
    }
}
