<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

class Link implements IMessage
{
    use Manageable;

    const TYPE = 'link';

    public function __construct(
        public string  $chatId,
        public string  $urlLink,
        public ?string $quotedMessageId = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'urlLink' => $this->urlLink,
            'quotedMessageId' => $this->quotedMessageId,
        ];
    }
}