<?php

namespace App\Events\Whatsapp\Webhooks;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCall
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $webhookPayload)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
