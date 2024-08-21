<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\WhatsappInstance;

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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $instanceName = "Инстанс N".($user->whatsappInstances()->count() + 1);

        $instance = $this->instanceManager->create($instanceName);

        return WhatsappInstance::query()->create([
            'id' => $instance->id,
            'user_id' => auth()->id(),
            'token' => $instance->token,
        ]);
    }
}
