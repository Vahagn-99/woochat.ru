<?php

namespace App\Listeners\GreenApi\Webhooks;

use App\Events\GreenApi\Webhooks\IncomingCall;
use App\Models\AmoConnection;
use App\Models\Chat;
use App\Models\Instance;
use App\Models\User;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\Types\Actor;
use App\Services\AmoChat\Messaging\Types\Message;
use App\Services\AmoChat\Messaging\Types\Text;

class HandleIncomingCall
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IncomingCall $event): void
    {
    }
}
