<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

class Contact implements IMessage
{
    use Manageable;

    const TYPE = 'contact';

    public function __construct(
        public string  $chatId,
        public array   $contact,
        public ?string $quotedMessageId = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'contact' => $this->contact,
            'quotedMessageId' => $this->quotedMessageId,
        ];
    }
}