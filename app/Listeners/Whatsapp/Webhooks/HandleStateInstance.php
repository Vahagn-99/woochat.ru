<?php

namespace App\Listeners\Whatsapp\Webhooks;

use App\Events\Whatsapp\Webhooks\StateInstanceChanged;
use App\Models\Instance;

class HandleStateInstance
//    implements ShouldQueue
{
//    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(StateInstanceChanged $event): void
    {

        $instanceData = $event->webhookPayload['instanceData'];
        $status = $event->webhookPayload['stateInstance'];

        $instance = Instance::query()->findOrFail($instanceData['idInstance']);

        $instance->status = $status;

        if ($instanceData['wid']) {
            $instance->phone = $instanceData['wid'];
        }

        $instance->save();
    }
}
