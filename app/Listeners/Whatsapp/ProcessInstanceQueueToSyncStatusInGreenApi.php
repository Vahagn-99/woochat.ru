<?php

namespace App\Listeners\Whatsapp;

use App\Events\Whatsapp\InstanceCreated;
use Illuminate\Support\Facades\Artisan;

class ProcessInstanceQueueToSyncStatusInGreenApi
{
    public function __construct()
    {
    }

    public function handle(InstanceCreated $event): void
    {
        Artisan::queue("sync:instance", ['instance' => $event->instance->id])
            ->afterCommit()
            ->afterResponse();
    }
}
