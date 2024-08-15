<?php

namespace App\Models;

use App\Enums\PlanDurationPeriod as SubscriptionPlanDurationPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @propery int $id
 * @propery string $name
 * @propery string $price
 * @propery string $duration
 * @propery string $trial_duration
 * @propery string $duration_period
 */
class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'price',
        'duration',
        'trial_duration',
        'duration_period',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration_period' => SubscriptionPlanDurationPeriod::class,
    ];

    /**
     * Получение ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
