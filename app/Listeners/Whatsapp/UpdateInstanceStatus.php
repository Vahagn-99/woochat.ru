<?php

namespace App\Listeners\Whatsapp;

use App\Events\Whatsapp\InstanceStatusChanged;
use App\Models\WhatsappInstance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInstanceStatus implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(InstanceStatusChanged $event): void
    {

        $instanceData = $event->webhookPayload['instanceData'];
        $status = $event->webhookPayload['stateInstance'];

        $instance = WhatsappInstance::query()->findOrFail($instanceData['idInstance']);

        $instance->status = $status;

        if ($instanceData['wid']) {
            $instance->phone = $instanceData['wid'];
        }

        $instance->save();
    }
}
