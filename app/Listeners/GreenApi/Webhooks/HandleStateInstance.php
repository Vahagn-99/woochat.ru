<?php

namespace App\Listeners\GreenApi\Webhooks;

use App\Events\GreenApi\Webhooks\StateInstanceChanged;
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
