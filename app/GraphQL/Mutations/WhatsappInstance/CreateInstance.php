<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\WhatsappInstance;

use App\Enums\InstanceStatus;
use App\Events\Whatsapp\NewInstanceOrdered;
use App\Models\WhatsappInstance;
use App\Services\Whatsapp\Facades\Whatsapp;

final readonly class CreateInstance
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): WhatsappInstance
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $model = WhatsappInstance::whereFree()->first();

        $status = InstanceStatus::NOT_AUTHORIZED;
        $name = "Инстанс No".WhatsappInstance::query()->count();

        // if there is no free instance then create one
        if (! $model) {
            $instance = Whatsapp::instance()->create($name);
            $status = InstanceStatus::STARTING;
            WhatsappInstance::query()->create([
                'id' => $instance->id,
                'user_id' => $user->id,
                'token' => $instance->token,
                'status' => $status,
            ]);
        }

        if ($status !== InstanceStatus::STARTING) {
            Whatsapp::for([
                'id' => $model->id,
                'token' => $model->token,
            ])->api()->clearQueue();
        }

        NewInstanceOrdered::dispatchIf(WhatsappInstance::whereFree()->count() <= 1, $name);

        $model->user_id = $user->id;
        $model->save();

        return $model;
    }
}
