<?php

declare(strict_types=1);

namespace App\Base\Subscription;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class SubscribedDto extends Data
{
    public function __construct(
        public string $domain,
        public int $subscription_id,
        public Carbon $expired_at,
        public int $max_instances_count = 1,
        public int $current_instances_count = 1,
    ) {
    }
}
