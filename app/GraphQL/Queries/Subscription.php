<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Carbon;

final readonly class Subscription
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): string
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return $user->activeSubscription?->expired_at?->format('Y-m-d');
    }
}
