<?php

declare(strict_types=1);

namespace App\Base\Subscription;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class SubscriptionDto extends Data
{
    public function __construct(
        public string $user_domain,
        public Carbon $expired_at,
    ) {
    }
}
