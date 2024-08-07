<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Chat\Message\Manageable;

class Forwards implements Payload
{
    use Manageable;

    /**
     * @param array<Payload> $messages
     */
    public function __construct(
        public string  $chatId,
        public array   $messages,
        public string  $conversation_ref_id,
        public ?string $conversation_id = null,
        public string  $text = '',
    )
    {
    }

    public function getType(): string
    {
        return 'forwards';
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'messages' => array_map(fn(Payload $message) => $message->toArray(), $this->messages),
            'conversation_ref_id' => $this->conversation_ref_id,
            'conversation_id' => $this->conversation_id,
            'text' => $this->text,
        ]);
    }
}