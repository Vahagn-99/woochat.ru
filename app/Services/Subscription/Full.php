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

        if ($trial = $user->active_trial_subscription?->archive()) {
            $trial->archive();
        }

        if ($active = $user->active_subscription) {
            $active->expired_at = $subscription_dto->expired_at;
            $active->save();

            return new SubscribedDto(
                $user->domain,
                $active->id,
                $active->expired_at,
                $subscription_dto->max_instances_count,
                $user->whatsapp_instances()->count()
            );
        }

        if ($last = $user->last_subscription) {
            $last->expired_at = $subscription_dto->expired_at;
            $last->status = SubscriptionStatus::ACTIVE;
            $last->save();

            return new SubscribedDto(
                $user->domain,
                $last->id,
                $last->expired_at,
                $subscription_dto->max_instances_count,
                $user->whatsapp_instances()->count()
            );
        }

        $subscription = new SubscriptionModel();
        $subscription->domain = $user->domain;
        $subscription->expired_at = $subscription_dto->expired_at;
        $subscription->is_trial = 0;
        $subscription->save();

        $user->whatsapp_instances->each(function (WhatsappInstance $instance) {
            $instance->status = InstanceStatus::AUTHORIZED;

            $instance->save();
        });

        $user->refresh();

        return new SubscribedDto(
            $user->domain,
            $subscription->id,
            $subscription->expired_at,
            $user->max_instances_count,
            $user->whatsapp_instances->count()
        );
    }
}
