<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\WhatsappInstance;

use App\Enums\InstanceStatus;
use App\Events\Whatsapp\NewInstanceOrdered;
use App\Models\WhatsappInstance;
use App\Models\WhatsappInstance as InstanceModel;
use App\Services\Whatsapp\Facades\Whatsapp;

final readonly class CreateInstance
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): WhatsappInstance
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $usedInstances = InstanceModel::query()->select('id')->pluck('id')->toArray();

        $instance = Whatsapp::instance()->getLastFree($usedInstances);

        $status = InstanceStatus::NOT_AUTHORIZED;
        $name = "Инстанс No".count($usedInstances);

        // if there is no free instance then create one
        if (! $instance) {
            $instance = Whatsapp::instance()->create($name);
            $status = InstanceStatus::STARTING;
        }

        if ($status !== InstanceStatus::STARTING) {
            Whatsapp::for([
                'id' => $instance->id,
                'token' => $instance->token,
            ])->api()->clearQueue();
        }

        event(NewInstanceOrdered::class);

        return WhatsappInstance::query()->create([
            'id' => $instance->id,
            'user_id' => $user->id,
            'token' => $instance->token,
            'status' => $status,
        ]);
    }
}
