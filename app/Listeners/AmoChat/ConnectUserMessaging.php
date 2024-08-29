<?php

namespace App\Listeners\AmoChat;

use App\Events\AmoCRM\UserCreated;
use App\Models\User;
use App\Services\AmoChat\Facades\AmoChat;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConnectUserMessaging implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserCreated $event): void
    {
        try {
            $this->connectAmoInstance($event->user);
        } catch (Exception $e) {
            do_log('widget/error/connect')->error($e->getMessage());

            return;
        }
    }

    private function connectAmoInstance(User $user): void
    {
        $instance = AmoChat::connector()->connect($user->amojo_id);

        $user->amoInstance()->updateOrCreate(['account_id' => $instance->account_id], [
            'scope_id' => $instance->scope_id,
            'title' => $instance->title,
        ]);
    }
}
