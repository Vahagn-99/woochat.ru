<?php

namespace App\Listeners\GreenApi\Webhooks;

use App\Events\GreenApi\Webhooks\OutgoingMessageReceived;

class HandleOutgoingMessage
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
    public function handle(OutgoingMessageReceived $event): void
    {
        //TODO: send message to amocrm
    }
}
