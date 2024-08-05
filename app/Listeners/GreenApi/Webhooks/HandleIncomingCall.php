<?php

namespace App\Listeners\GreenApi\Webhooks;

use App\Events\GreenApi\Webhooks\IncomingCall;

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
        //
    }
}
