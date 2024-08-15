<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $subscription_plan_id
 * @property string $status
 * @property string $trial_ends_up
 * @property string $expire_at
 *
 * @property-read User $user
 * @property-read SubscriptionPlan $subscriptionPlan
 */
class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'trial_ends_up',
        'expire_at',
    ];

    protected $casts = [
        'expire_at' => 'datetime',
        'trial_ends_up' => 'datetime',
        'status' => SubscriptionStatus::class,
    ];

    /**
     * Пользователь.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * План подписки.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Получение цены.
     *
     * @return int
     */
    public function getPrice(): int
    {
        return $this->subscriptionPlan->price;
    }
}
