<?php

namespace App\Models;

use App\Base\Subscription\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $domain
 * @property string $is_trial
 * @property SubscriptionStatus $status
 * @property \Illuminate\Support\Carbon $expired_at
 * @property \Illuminate\Support\Carbon $created_at
 *
 * @property-read User $user
 */
class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    public $timestamps = false;
    protected $fillable = [
        'domain',
        'is_trial',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'status' => SubscriptionStatus::class,
    ];

    public function archive(): static
    {
        $this->status = SubscriptionStatus::EXPIRE;
        $this->save();
        return $this;
    }
}
