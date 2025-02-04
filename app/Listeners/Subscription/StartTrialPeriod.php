<?php

namespace App\Listeners\Subscription;

use App\Base\Subscription\SubscriptionDto;
use App\Services\Subscription\Trial as TrialSubscriptionService;
use App\Events\Subscription\Trial as TrialEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StartTrialPeriod implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly TrialSubscriptionService $subscription_trial_service
    ) {
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'subscription';
    }

    /**
     * @throws \App\Exceptions\Subscription\SubscriptionException
     */
    public function handle(TrialEvent $event): void
    {
        $this->subscription_trial_service->subscribe(new SubscriptionDto($event->user->domain, $event->expired_at));
    }
}
