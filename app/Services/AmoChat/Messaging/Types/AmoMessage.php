<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\IMessage as BaseMessage;
use App\Base\Messaging\Manageable;
use App\Services\AmoChat\Messaging\Actor\Actor;
use App\Services\AmoChat\Messaging\Source\Source;

class AmoMessage implements BaseMessage
{
    use Manageable;

    public function __construct(
        public Actor $sender,
        public IMessage $payload,
        public ?Source $source = null,
        public bool $silent = false,
        public ?Actor $receiver = null,
        public ?string $conversation_id = null,
        public ?string $conversation_ref_id = null,
        public string $event_type = 'new_message',
        public ?string $id = null,
        public ?string $msgid = null,
    ) {
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
                'silent' => $this->silent,
                'source' => $this->source?->toArray(),
            ], fn($item) => ! is_null($item)),
        ];
    }

    public function getType(): string
    {
        return $this->payload->getType();
    }
}