<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

class ListMessage implements IMessage
{
    use Manageable;

    const TYPE = 'listMessage';

    public function __construct(
        public string  $chatId,
        public string  $message,
        public array   $sections,
        public ?string $title = null,
        public ?string $footer = null,
        public ?string $buttonText = null,
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
            'sections' => $this->sections,
            'title' => $this->title,
            'footer' => $this->footer,
            'buttonText' => $this->buttonText,
            'quotedMessageId' => $this->quotedMessageId,
            'archiveChat' => $this->archiveChat,
        ];
    }
}