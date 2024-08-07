<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

class Button implements IMessage
{
    use Manageable;

    const TYPE = 'button';

    public function __construct(
        public string  $chatId,
        public string  $message,
        public string  $footer,
        public array   $buttons,
        public ?string $quotedMessageId = null,
        public bool    $archiveChat = false
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'message' => $this->message,
            'footer' => $this->footer,
            'buttons' => $this->buttons,
            'quotedMessageId' => $this->quotedMessageId,
            'archiveChat' => $this->archiveChat,
        ];
    }
}