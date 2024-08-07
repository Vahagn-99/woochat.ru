<?php

namespace App\Listeners\GreenApi\Webhooks;

use App\Events\GreenApi\Webhooks\OutgoingMessageReceived;
use App\Models\AmoConnection;
use App\Models\Chat;
use App\Models\Instance;
use App\Models\User;
use App\Services\AmoChat\Chat\Create\CreateAmoChatDTO;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\MessageResponse;
use App\Services\AmoChat\Messaging\Types\Actor;
use App\Services\AmoChat\Messaging\Types\Message;
use App\Services\AmoChat\Messaging\Types\Text;

class HandleOutgoingMessage
{
    public function __construct()
    {
        //
    }

    public function handle(OutgoingMessageReceived $event): void
    {

    }
}
