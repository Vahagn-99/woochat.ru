<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Instance;

use App\Models\Instance;
use App\Services\GreenApi\Facades\GreenApi;
use App\Services\GreenApi\Instance\InstanceServiceInterface;

final readonly class DeleteInstance
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): bool
    {
        $instance = Instance::query()->find($args['id']);
        GreenApi::fromModel($instance)->api()->getClient()->account->logout();

        return $instance->delete();
    }
}
