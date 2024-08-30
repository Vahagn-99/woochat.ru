<?php

namespace App\Listeners\Messengers\Whatsapp;

use App\Events\Messengers\Whatsapp\InstanceCreated;
use Illuminate\Support\Facades\Artisan;

class SyncInstanceStatus
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
