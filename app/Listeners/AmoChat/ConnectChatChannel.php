<?php

namespace App\Listeners\AmoChat;

use App\Events\AmoChat\UserCreated;
use App\Services\AmoChat\Facades\AmoChat;

class ConnectChatChannel
{

    public function __construct()
    {

    }

    public function handle(UserCreated $event): void
    {
        $user = $event->user;
        $connection = AmoChat::connector()->connect($user->amojo_id);

        $user->amoConnections()
            ->updateOrCreate(['account_id' => $connection->account_id], [
                'scope_id' => $connection->scope_id,
                'title' => $connection->title
            ]);
    }
}
