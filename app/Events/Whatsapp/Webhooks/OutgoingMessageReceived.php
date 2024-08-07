<?php

namespace App\Events\Whatsapp\Webhooks;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OutgoingMessageReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $webhookPayload)
    {
    }
}
