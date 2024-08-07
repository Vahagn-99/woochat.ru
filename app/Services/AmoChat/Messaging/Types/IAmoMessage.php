<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Chat\Message\IMessage as BaseMessage;
use App\Base\Chat\Message\Manageable;

class IAmoMessage implements BaseMessage
{
    use Manageable;

    public function __construct(
        public Actor   $sender,
        public Payload $payload,
        public bool    $silent,
        public ?Actor  $receiver = null,
        public ?string $conversation_id = null,
        public ?string $conversation_ref_id = null,
        public string  $event_type = 'new_message',
        public ?string $id = null,
        public ?string $msgid = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'event_type' => $this->event_type,
            'payload' => array_filter([
                'id' => $this->id,
                'timestamp' => now()->getTimestamp(),
                'msec_timestamp' => now()->getTimestampMs(),
                'msgid' => $this->msgid,
                'conversation_id' => $this->conversation_id,
                'conversation_ref_id' => $this->conversation_ref_id,
                'sender' => $this->sender->toArray(),
                'receiver' => $this->receiver?->toArray(),
                'message' => $this->payload->toArray(),
                'silent' => $this->silent
            ], fn($item) => !is_null($item))
        ];
    }

    public function getType(): string
    {
        return $this->payload->getType();
    }

    public function getChatId(): string
    {
        return $this->conversation_id;
    }
}