<?php

namespace App\Listeners\AmoChat;

use App\Events\AmoCRM\UserDeleted;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DisconnectUserMessaging implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserDeleted $event): void
    {
        $instances = $event->user->instances;
        foreach ($instances as $instance) {
            Whatsapp::for($instance)->instance()->logout();
        }

        // delete user instances
        $event->user->whatsappInstances()->delete();

        // delete amochat connections
        $event->user->amoInstances()->delete();

        // delete user access tokens
        $event->user->amoAccessToken()->delete();
    }
}
