<?php

namespace App\Listeners\Messengers\AmoChat;

use App\Events\Messaging\UserDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class DisconnectChannel implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserDeleted $event): void
    {
        // delete amochat instance
        $instance = $event->user->amo_instance;
        try {
            $instance->delete();
            do_log('amochat/instance')->info("Канал {$instance->id} успешно удалень.");
        } catch (Throwable $e) {
            do_log('amochat/instance')->error("Не удалось отключить канал {$instance} .", [
                'причина' => $e->getMessage(),
            ]);

            return;
        }
    }
}
