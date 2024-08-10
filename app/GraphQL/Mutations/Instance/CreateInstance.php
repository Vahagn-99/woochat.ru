<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Instance;

use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Instance\InstanceServiceInterface;

final readonly class CreateInstance
{
    public function __construct(private InstanceServiceInterface $instanceManager)
    {
    }

    /** @param array{} $args */
    public function __invoke(null $_, array $args): WhatsappInstance
    {
        $instanceName = $args['input']['name'];
        $instance = $this->instanceManager->create($instanceName);

       return WhatsappInstance::query()->create([
            'id' => $instance->id,
            'name' => $instanceName,
            'user_id' => auth()->id(),
            'token' => $instance->token,
        ]);
    }
}
