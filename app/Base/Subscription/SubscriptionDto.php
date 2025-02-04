<?php

declare(strict_types=1);

namespace App\Base\Subscription;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class SubscriptionDto extends Data
{
    /**
     * @param string $user_domain
     * @param \Illuminate\Support\Carbon $expired_at
     * @param int $max_instances_count
     */
    public function __construct(
        public string $user_domain,
        public Carbon $expired_at,
        public int $max_instances_count = 1,
    ) {
    }
}
