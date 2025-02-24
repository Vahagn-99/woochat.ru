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
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property int $user_id
 * @property InstanceStatus $status
 * @property string $token
 * @property string $phone
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $blocked_at
 *
 * @property-read User $user
 * @property-read \App\Models\Settings $settings
 *
 * @method static Builder whereFree()
 * @method static Builder whereBlocked()
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
        'blocked_at',
    ];

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'status' => InstanceStatus::class,
        'created_at' => 'date',
        'blocked_at' => 'date',
    ];

    public static function firstInAccount(User $user) : ?WhatsappInstance
    {
        /** @var ?WhatsappInstance */
        return $user->whatsapp_instances()->where('status', InstanceStatus::AUTHORIZED)->first();
    }

    protected static function booted() : void
    {
        WhatsappInstance::created(fn(WhatsappInstance $instance) => InstanceCreated::dispatch($instance));
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function settings() : HasOne
    {
        return $this->hasOne(Settings::class, 'instance_id', 'id');
    }

    public function chats() : HasMany
    {
        return $this->hasMany(Chat::class, 'whatsapp_instance_id', 'id');
    }

    public function scopeWhereFree(Builder $query) : Builder
    {
        return $query->where('status', InstanceStatus::NOT_AUTHORIZED);
    }

    public function scopeWhereBlocked(Builder $query) : Builder
    {
        return $query->where('status', InstanceStatus::BLOCKED);
    }

    public static function dto(string $instanceId) : InstanceDTO
    {
        /** @var WhatsappInstance $instance */
        $instance = self::query()->findOrFail($instanceId);

        return new InstanceDTO($instance->id, $instance->token);
    }

    public function transformToDto() : InstanceDTO
    {
        return new InstanceDTO($this->id, $this->token);
    }

    public function clearPhone() : string
    {
        return "+".Str::numbers($this->phone);
    }
}
