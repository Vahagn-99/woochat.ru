<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Services\GreenApi\Messaging\Types\HasContent;

class ReplyTo implements Payload
{
    use HasContent;

    public function __construct(
        public string  $chatId,
        public Payload $message,
        public string  $text = '',
    )
    {
    }

    public function getType(): string
    {
        return 'text';
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'message' => $this->message->toArray(),
            'text' => $this->text,
        ]);
    }
}