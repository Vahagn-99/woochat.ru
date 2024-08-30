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
        try {
            // delete amochat instance
            $event->user->amoInstance()->delete();
            do_log('widget/success_delete')->info("Канал {$event->user->amoInstance->id} успешно удалень.");
        } catch (Throwable $e) {
            do_log('widget/error_delete/amochat'.now()->toDateTimeString())->error("Не удалось отключить канал {$event->user->amoInstance->id} .", [
                'причина' => $e->getMessage(),
            ]);

            return;
        }
    }
}
