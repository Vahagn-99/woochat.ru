<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\Enums\InstanceStatus;
use App\Base\Subscription\{
    SubscribedDto,
    Subscription,
    SubscriptionDto,
    SubscriptionStatus
};
use App\Models\Subscription as SubscriptionModel;
use App\Models\User as UserModel;
use App\Models\WhatsappInstance;

class Full implements Subscription
{
    public function subscribe(SubscriptionDto $subscription_dto): SubscribedDto
    {
        /** @var \App\Models\User $user */
        $user = UserModel::query()->findOrFail($subscription_dto->user_domain);

        $user->max_instances_count = $subscription_dto->max_instances_count;
        $user->save();

        if ($trial = $user->activeTrialSubscription?->archive()) {
            $trial->archive();
        }

        if ($active = $user->activeSubscription) {
            $active->expired_at = $subscription_dto->expired_at;
            $active->save();

            return new SubscribedDto(
                $user->domain,
                $active->id,
                $active->expired_at,
                $subscription_dto->max_instances_count,
                $user->whatsappInstances()->count()
            );
        }

        if ($last = $user->lastSubscription) {
            $last->expired_at = $subscription_dto->expired_at;
            $last->status = SubscriptionStatus::ACTIVE;
            $last->save();

            return new SubscribedDto(
                $user->domain,
                $last->id,
                $last->expired_at,
                $subscription_dto->max_instances_count,
                $user->whatsappInstances()->count()
            );
        }

        $subscription = new SubscriptionModel();
        $subscription->domain = $user->domain;
        $subscription->expired_at = $subscription_dto->expired_at;
        $subscription->is_trial = 0;
        $subscription->save();

        $user->whatsappInstances->each(function (WhatsappInstance $instance) {
            $instance->status = InstanceStatus::AUTHORIZED;

            $instance->save();
        });

        $user->refresh();

        return new SubscribedDto(
            $user->domain,
            $subscription->id,
            $subscription->expired_at,
            $user->max_instances_count,
            $user->whatsappInstances->count()
        );
    }
}
