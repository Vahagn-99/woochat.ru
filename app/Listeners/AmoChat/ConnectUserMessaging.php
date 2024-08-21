<?php

namespace App\Listeners\AmoChat;

use App\Enums\InstanceStatus;
use App\Events\AmoCRM\UserCreated;
use App\Models\User;
use App\Models\WhatsappInstance as InstanceModel;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\Whatsapp\Facades\Whatsapp;
use App\Services\Whatsapp\Instance\CreatedInstanceDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class ConnectUserMessaging implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserCreated $event): void
    {
        $this->connectAmoInstance($event->user);
        $this->connectWhatsappInstance($event->user);
    }

    private function connectAmoInstance(User $user): void
    {
        $instance = AmoChat::connector()->connect($user->amojo_id);

        $user->amoInstance()->updateOrCreate(['account_id' => $instance->account_id], [
            'scope_id' => $instance->scope_id,
            'title' => $instance->title,
        ]);
    }

    private function connectWhatsappInstance(User $user): void
    {
        $allInstances = Whatsapp::instance()->all();
        $usedInstances = InstanceModel::query()->select('id')->pluck('id')->toArray();
        $freeInstances = Arr::where($allInstances, fn(CreatedInstanceDTO $item
        ) => ! in_array($item->id, $usedInstances));

        $firstFreeInstance = current($freeInstances);

        $status = InstanceStatus::NOT_AUTHORIZED;
        $name = "Мой Первый инстанс";

        // if there is no free instance then create one
        if (! $firstFreeInstance) {
            $firstFreeInstance = Whatsapp::instance()->create($name);
            $status = InstanceStatus::STARTING;
        }

        $user->whatsappInstances()->create([
            'id' => $firstFreeInstance->id,
            'user_id' => $user->id,
            'status' => $status,
            'token' => $firstFreeInstance->token,
        ]);
    }
}
