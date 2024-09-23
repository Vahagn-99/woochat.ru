<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\Base\Subscription\{SubscribedDto, Subscription, SubscriptionDto,};
use App\Exceptions\Subscription\SubscriptionException;
use App\Models\Subscription as SubscriptionModel;
use App\Models\User as UserModel;

class Trial implements Subscription
{
    /**
     * @throws \App\Exceptions\Subscription\SubscriptionException
     */
    public function subscribe(SubscriptionDto $subscription_dto): SubscribedDto
    {
        $user = UserModel::query()->findOrFail($subscription_dto->user_domain);

        if ($user->trialSubscription()->exists()) {
            SubscriptionException::alreadyHasTrial($user);
        }

        if ($user->activeSubscription()->exists()) {
            SubscriptionException::hasActive($user);
        }

        $user->max_instances_count = $subscription_dto->max_instances_count;
        $user->save();

        $subscription = new SubscriptionModel();
        $subscription->domain = $user->domain;
        $subscription->expired_at = $subscription_dto->expired_at;
        $subscription->is_trial = true;
        $subscription->save();

        return new SubscribedDto($user->domain, $subscription->id, $subscription->expired_at);
    }
}
