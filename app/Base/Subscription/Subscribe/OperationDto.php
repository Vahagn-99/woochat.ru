<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe;

use App\Models\SubscriptionPlan as SubscriptionPlanModel;
use App\Models\User as UserModel;

final readonly class OperationDto
{
    /**
     * OperationDto construct.
     *
     * @param \App\Models\User $user
     * @param \App\Models\SubscriptionPlan $subscription_plan
     * @param bool $is_paid
     */
    public function __construct(
        public UserModel $user,
        public SubscriptionPlanModel $subscription_plan,
        public bool $is_paid = false,
    ) {
    }
}
