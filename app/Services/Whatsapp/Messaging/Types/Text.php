<?php

namespace App\Services\Whatsapp\Messaging\Types;


use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

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
            'chat_id' => $this->chatId,
            'message' => $this->message,
            'quoted_message_id' => $this->quotedMessageId,
        ];
    }
}