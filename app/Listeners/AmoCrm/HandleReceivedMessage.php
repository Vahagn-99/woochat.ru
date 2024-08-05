<?php

namespace App\Listeners\AmoCrm;

use App\Events\AmoCrm\MessageReceived;

class HandleReceivedMessage
{

    public function handle(MessageReceived $event): void
    {
        // get whatsapp instance id by chat id
        // init green api
        // send message to whatsapp
        // save message id from whatsapp in message model
    }

}
