<?php

namespace App\Listeners\Whatsapp\Webhooks;

use App\Events\Whatsapp\Webhooks\OutgoingMessageReceived;

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
