<?php

namespace App\Listeners\Messengers\AmoChat;

use App\Events\Messengers\AmoChat\ChannelRequested;
use App\Models\User;
use App\Services\AmoChat\Facades\AmoChat;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConnectChannel implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ChannelRequested $event): void
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