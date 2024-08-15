<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionStatus : int
{
    case ACTIVE = 1;
    case TRIAL = 2;
    case EXPIRE = 3;
}
