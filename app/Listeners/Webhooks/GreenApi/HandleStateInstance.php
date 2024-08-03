<?php

namespace App\Listeners\Webhooks\GreenApi;

use App\Events\Webhooks\GreenApi\StateInstanceChanged;
use App\Models\Instance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleStateInstance implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(StateInstanceChanged $event): void
    {

        $instanceData = $event->webhookPayload['instanceData'];
        $status = $event->webhookPayload['stateInstance'];

        $instance = Instance::query()->findOrFail($instanceData['idInstance']);
        $instance->update([
            'status' => $status,
            'phone' => $instanceData['wid']
        ]);
    }
}
