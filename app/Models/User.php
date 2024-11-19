<?php

namespace App\Models;

use App\Base\Subscription\SubscriptionStatus;
use App\DTO\NewAmoUserDTO;
use App\Exceptions\Settings\NewInstanceException;
use App\Services\AmoCRM\Core\Account\AmoAccountInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use League\OAuth2\Client\Token\AccessToken;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

/**
 * @property int $id
 * @property string $domain
 * @property string $amojo_id
 * @property string $deleted_at
 * @property int $max_instances_count
 * @property string $email
 * @property string $phone
 * @property string $country
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\WhatsappInstance> whatsapp_instances
 * @property-read \App\Models\AmoInstance $amo_instance
 * @property-read \App\Models\AmoAccessToken $amo_access_token
 * @property-read \App\Models\Info $info
 * @property-read \App\Models\Subscription $active_trial_subscription
 * @property-read \App\Models\Subscription $active_subscription
 * @property-read \App\Models\Subscription $last_subscription
 * @property-read \App\Models\Subscription $trial_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Subscription> $subscriptions
 */
final class User extends Authenticatable implements AmoAccountInterface
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasFlags;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'domain',
        'email',
        'phone',
        'country',
        'amojo_id',
        'deleted_at',
        'max_instances_count',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'domain';

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     *******************************************
     ************** Отнашени я******************
     *******************************************
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function whatsapp_instances(): HasMany
    {
        return $this->hasMany(WhatsappInstance::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function amo_instance(): HasOne
    {
        return $this->hasOne(AmoInstance::class, 'account_id', 'amojo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function amo_access_token(): HasOne
    {
        return $this->hasOne(AmoAccessToken::class, 'domain', 'domain');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'domain', 'domain');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function active_subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where('status', SubscriptionStatus::ACTIVE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function last_subscription(): HasOne
    {
        return $this->many(Subscription::class, 'domain', 'domain')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function active_paid_subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where('status', SubscriptionStatus::ACTIVE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trial_subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where('is_trial', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function active_trial_subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where('status', SubscriptionStatus::ACTIVE)->where('is_trial', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function Info(): MorphOne
    {
        return $this->morphOne(Info::class, 'infoable', "infoable_type", 'infoable_id', 'domain');
    }

    /**
     *******************************************
     ************** Скопы я******************
     *******************************************
     */


    /**
     *******************************************
     ************** Методы я******************
     *******************************************
     */

    /**
     * @param string $domain
     * @return \App\Models\User|null
     */
    public static function getByDomainOrCreate(string $domain): ?User
    {
        /** @var \App\Models\User $user */
        $user = self::withTrashed()->where('domain', $domain)->first();

        if (! $user) {
            $user = new User();
            $user->domain = $domain;

            $user->save();
        } elseif ($user->trashed()) {
            $user->restore();
        }

        return $user;
    }

    /**
     * @param \App\DTO\NewAmoUserDTO $data
     * @return \App\Models\User|null
     */
    public static function getByDomainOrId(NewAmoUserDTO $data): ?User
    {
        /** @var User $user */
        $user = User::withTrashed()->where('id', $data->id)->orWhere('domain', $data->domain)->first();
        if ($user && $user->trashed()) {
            $user->restore();
        }

        return $user;
    }

    /**
     * @param string $amojo_id
     * @return \App\Models\User|null
     */
    public static function getByAmojoId(string $amojo_id): ?User
    {
        /** @var User $user */
        $user = User::withTrashed()->where('amojo_id', $amojo_id)->first();

        if ($user && $user->trashed()) {
            $user->restore();
        }

        return $user;
    }

    /**
     * @param int $instance_id
     * @return \App\Models\User|null
     */
    public static function getByWhatsappInstanceId(int $instance_id): ?User
    {
        /** @var User $user */
        $user = User::withTrashed()->whereHas('whatsapp_instances', fn($query) => $query->where('id', $instance_id))->first();

        if ($user && $user->trashed()) {
            $user->restore();
        }

        return $user;
    }

    /**
     * @return \League\OAuth2\Client\Token\AccessToken|null
     */
    public function getAccessToken(): ?AccessToken
    {
        return $this->amo_access_token?->getAccessToken();
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @throws \App\Exceptions\Settings\NewInstanceException
     */
    public function ensureHasFreeInstanceSlot(): void
    {
        if ($this->whatsapp_instances()->count()
            >= $this->max_instances_count) {
            throw NewInstanceException::limitOver($this->max_instances_count);
        }
    }
}
