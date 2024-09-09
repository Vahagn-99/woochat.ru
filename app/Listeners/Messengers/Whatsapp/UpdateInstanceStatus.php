<?php

namespace App\Listeners\Messengers\Whatsapp;

use App\Events\Messengers\Whatsapp\InstanceStatusChanged;
use App\Models\WhatsappInstance;
use Exception;
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
        try {

            $instance = WhatsappInstance::query()->findOrFail($instanceData['idInstance']);

            $instance->status = $status;

            if ($instanceData['wid']) {
                $instance->phone = $instanceData['wid'];
            }

            $instance->save();
        } catch (Exception $e) {
            do_log("whatsapp/instances")->error($e->getMessage());
            $this->release();

            return;
        }
    }
}
