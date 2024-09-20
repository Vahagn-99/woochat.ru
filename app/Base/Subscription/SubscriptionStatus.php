<?php

declare(strict_types=1);

namespace App\Base\Subscription;

enum SubscriptionStatus: int
{
    case ACTIVE = 1;
    case EXPIRE = 2;
}
