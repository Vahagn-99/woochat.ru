<?php

namespace App\Events\Whatsapp;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceStatusChanged implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $webhookPayload)
    {
    }

    /**
     * Get the channel the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('instances.'.$this->webhookPayload['instanceData']['idInstance']);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'instance.status-changed';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->webhookPayload['instanceData']['idInstance'],
            'status' => $this->webhookPayload['stateInstance'],
        ];
    }
}
