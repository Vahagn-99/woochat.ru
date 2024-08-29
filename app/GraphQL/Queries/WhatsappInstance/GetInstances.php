<?php declare(strict_types=1);

namespace App\GraphQL\Queries\WhatsappInstance;

final readonly class GetInstances
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $instances = $user->whatsappInstances()->get();

        return $instances->toArray();
    }
}
