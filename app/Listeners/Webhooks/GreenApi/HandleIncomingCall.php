<?php

namespace App\Listeners\Webhooks\GreenApi;

use App\Events\Webhooks\GreenApi\IncomingCall;

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
