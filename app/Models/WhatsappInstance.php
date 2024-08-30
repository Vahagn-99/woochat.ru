<?php

namespace App\Models;

use App\Enums\InstanceStatus;
use App\Events\Messengers\Whatsapp\InstanceCreated;
use App\Services\Whatsapp\DTO\InstanceDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property int $user_id
 * @property InstanceStatus $status
 * @property string $token
 * @property string $phone
 *
 * @property-read User $user
 * @property-read \App\Models\Settings $settings
 *
 * @method static Builder whereFree()
 */
final class WhatsappInstance extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_instances';

    protected $fillable = [
        'id',
        'user_id',
        'status',
        'token',
        'phone',
    ];

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'status' => InstanceStatus::class,
    ];

    public static function firstInAccount(User $user): ?WhatsappInstance
    {
        /** @var ?WhatsappInstance */
        return $user->whatsappInstances()->where('status', InstanceStatus::AUTHORIZED)->first();
    }

    protected static function booted(): void
    {
        WhatsappInstance::created(fn(WhatsappInstance $instance) => InstanceCreated::dispatch($instance));
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
        return $this->hasMany(Chat::class, 'whatsapp_instance_id', 'id');
    }

    public function scopeWhereFree(Builder $query): Builder
    {
        return $query->whereNull('user_id');
    }

    public static function dto(string $instanceId): InstanceDTO
    {
        /** @var WhatsappInstance $instance */
        $instance = self::query()->findOrFail($instanceId);

        return new InstanceDTO($instance->id, $instance->token);
    }

    public function toDto(): InstanceDTO
    {
        return new InstanceDTO($this->id, $this->token);
    }
}
