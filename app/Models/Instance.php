<?php

namespace App\Models;

use App\Enums\InstanceStatus;
use App\Events\Whatsapp\InstanceCreated;
use App\Services\Whatsapp\DTO\InstanceDTO;
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
 *
 * @property User $user
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

    public static function firstInAccount(User $user): ?Instance
    {
        /** @var ?Instance */
        return $user->instances()->where('status', InstanceStatus::AUTHORIZED)->first();
    }

    protected static function booted(): void
    {
        Instance::created(fn(Instance $instance) => InstanceCreated::dispatch($instance));
    }

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

    public static function dto(string $instanceId): InstanceDTO
    {
        /** @var Instance $instance */
        $instance = self::query()->findOrFail($instanceId);
        return new InstanceDTO($instance->id, $instance->token);
    }

    public function toDto(): InstanceDTO
    {
        return new InstanceDTO($this->id, $this->token);
    }
}
