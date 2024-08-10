<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Messaging\Manageable;

class ReplyTo implements Payload
{
    use Manageable;

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