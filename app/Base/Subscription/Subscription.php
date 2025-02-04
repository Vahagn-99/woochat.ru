<?php

declare(strict_types=1);

namespace App\Base\Subscription;

interface Subscription
{
    public function subscribe(SubscriptionDto $subscription_dto): SubscribedDto;
}
