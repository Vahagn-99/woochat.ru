<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

final readonly class Subscription
{
    /**
     * @return string|null
     */
    public function __invoke(): ?string
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return $user->activeSubscription?->expired_at?->format('Y-m-d');
    }
}
