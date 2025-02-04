<?php

namespace App\Services\Whatsapp\Messaging\Types;


use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class Text implements IMessage
{
    use Manageable;

    const TYPE = 'message';

    public function __construct(
        public readonly string  $chatId,
        public readonly string  $message,
        public readonly ?string $quotedMessageId = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'message' => $this->message,
            'quotedMessageId' => $this->quotedMessageId,
        ];
    }
}