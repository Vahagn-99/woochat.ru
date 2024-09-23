<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final readonly class Subscription
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): \App\Models\Subscription
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return $user->activeSubscription?->expired_at;
    }
}
