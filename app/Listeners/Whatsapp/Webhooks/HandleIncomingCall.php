<?php

namespace App\Listeners\Whatsapp\Webhooks;

use App\Events\Whatsapp\Webhooks\IncomingCall;

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
