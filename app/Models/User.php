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
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\WhatsappInstance> whatsappInstances
 * @property-read \App\Models\AmoInstance $amoInstance
 * @property-read \App\Models\AmoAccessToken $amoAccessToken
 * @property-read \App\Models\Info $info
 * @property-read \App\Models\Subscription $activeTrialSubscription
 * @property-read \App\Models\Subscription $activeSubscription
 * @property-read \App\Models\Subscription $lastSubscription
 * @property-read \App\Models\Subscription $trialSubscription
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Subscription> $subscriptions
 */
final class User extends Authenticatable implements AmoAccountInterface
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasFlags;

    protected $table = 'users';

    public $incrementing = false;

    public $timestamps = false;

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

    protected $primaryKey = 'domain';

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function whatsappInstances(): HasMany
    {
        return $this->hasMany(WhatsappInstance::class, 'user_id', 'id');
    }

    public function amoInstance(): HasOne
    {
        return $this->hasOne(AmoInstance::class, 'account_id', 'amojo_id');
    }

    public function amoAccessToken(): HasOne
    {
        return $this->hasOne(AmoAccessToken::class, 'domain', 'domain');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'domain', 'domain');
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where('status', SubscriptionStatus::ACTIVE);
    }

    public function lastSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')
            ->latest();
    }

    public function activePaidSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where('status', SubscriptionStatus::ACTIVE);
    }

    public function trialSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where('is_trial', true);
    }

    public function activeTrialSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'domain', 'domain')->where(
            'status',
            SubscriptionStatus::ACTIVE
        )->where('is_trial', true);
    }

    public function Info(): MorphOne
    {
        return $this->morphOne(Info::class, 'infoable', "infoable_type", 'infoable_id', 'domain');
    }

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

    public static function getByDomainOrId(NewAmoUserDTO $data): ?User
    {
        /** @var User $user */
        $user = User::withTrashed()->where('id', $data->id)->orWhere('domain', $data->domain)->first();
        if ($user && $user->trashed()) {
            $user->restore();
        }

        return $user;
    }

    public function getAccessToken(): ?AccessToken
    {
        return $this->amoAccessToken?->getAccessToken();
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function hasNotificationInfo(): bool
    {
        return $this->info()->exists();
    }

    public function hasValidSubscription()
    {
    }

    /**
     * @throws \App\Exceptions\Settings\NewInstanceException
     */
    public function ensureHasFreeInstanceSlot(): void
    {
        if ($this->whatsappInstances()->count() >= $this->max_instances_count) {
            throw NewInstanceException::limitOver($this->max_instances_count);
        }
    }
}
