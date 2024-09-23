<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\Base\Subscription\{SubscribedDto, Subscription, SubscriptionDto,};
use App\Models\Subscription as SubscriptionModel;
use App\Models\User as UserModel;

class Full implements Subscription
{
    public function subscribe(SubscriptionDto $subscription_dto): SubscribedDto
    {
        /** @var \App\Models\User $user */
        $user = UserModel::query()->findOrFail($subscription_dto->user_domain);

        $user->max_instances_count = $subscription_dto->whatsapp_max_instances_count;
        $user->save();

        if ($trial = $user->activeTrialSubscription?->archive()) {
            $trial->archive();
        }

        if ($active = $user->activeSubscription) {
            $active->expired_at = $subscription_dto->expired_at;
            $active->save();

            return new SubscribedDto($user->domain, $active->id, $active->expired_at);
        }

        $subscription = new SubscriptionModel();
        $subscription->domain = $user->domain;
        $subscription->expired_at = $subscription_dto->expired_at;
        $subscription->is_trial = false;
        $subscription->save();

        return new SubscribedDto($user->domain, $subscription->id, $subscription->expired_at);
    }
}
