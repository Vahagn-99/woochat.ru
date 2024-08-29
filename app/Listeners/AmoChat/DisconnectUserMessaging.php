<?php

namespace App\Listeners\AmoChat;

use App\Events\AmoCRM\UserDeleted;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class DisconnectUserMessaging implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserDeleted $event): void
    {
        $instances = $event->user->instances;
        foreach ($instances as $instance) {
            Whatsapp::for($instance)->instance()->logout();
        }

        try {
            // delete user instances
            $event->user->whatsappInstances()->delete();

            // delete amochat instance
            $event->user->amoInstance()->delete();

            // delete user access tokens
            $event->user->amoAccessToken()->delete();
        } catch (Throwable $e) {
            do_log('widget/error/disconnect')->error($e->getMessage());

            return;
        }
    }
}
