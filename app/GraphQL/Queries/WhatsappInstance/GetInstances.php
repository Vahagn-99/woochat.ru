<?php declare(strict_types=1);

namespace App\GraphQL\Queries\WhatsappInstance;

use Illuminate\Database\Eloquent\Collection;

final readonly class GetInstances
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): Collection
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return $user->whatsappInstances()->get();
    }
}
