<?php

namespace App\Listeners\Webhooks\GreenApi;

use App\Events\Webhooks\GreenApi\IncomingMessageReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleIncomingMessage implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {

    }

    public function handle(IncomingMessageReceived $event): void
    {

    }
}
