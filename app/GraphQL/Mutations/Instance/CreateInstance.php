<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Instance;

use App\Models\Instance;
use App\Services\GreenApi\Instance\InstanceManagerInterface;

final readonly class CreateInstance
{
    public function __construct(private InstanceManagerInterface $instanceManager)
    {
    }

    /** @param array{} $args */
    public function __invoke(null $_, array $args): string
    {
        $instanceName = $args['input']['name'];
        $userId = $args['input']['user_id'];
        $instance = $this->instanceManager->create($instanceName);
        Instance::query()->create([
            'id' => $instance->id,
            'name' => $instanceName,
            'user_id' => $userId,
            'token' => $instance->token,
        ]);
        return 'success';
    }
}
