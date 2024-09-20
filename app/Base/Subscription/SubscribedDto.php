<?php

declare(strict_types=1);

namespace App\Base\Subscription;

use Illuminate\Support\Carbon;

class SubscribedDto
{
    public function __construct(
        public string $user_domain,
        public int $subscription_id,
        public Carbon $expired_at,
    ) {
    }
}
