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
            do_log('amochat/instance')->error($e->getMessage());

            $this->release();
        }
    }

    private function connectAmoInstance(User $user): void
    {
        $instance = AmoChat::connector()->connect($user->amojo_id);

        $user->amo_instance()->updateOrCreate(['account_id' => $instance->account_id], [
            'scope_id' => $instance->scope_id,
            'title' => $instance->title,
        ]);
    }
}
