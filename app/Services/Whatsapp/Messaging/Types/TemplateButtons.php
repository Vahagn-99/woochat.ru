<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

class TemplateButtons implements IMessage
{
    use Manageable;

    const TYPE = 'templateButton';

    public function __construct(
        public string  $chatId,
        public string  $message,
        public array   $templateButtons,
        public ?string $footer = null,
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
            'templateButtons' => $this->templateButtons,
            'footer' => $this->footer,
            'quotedMessageId' => $this->quotedMessageId,
            'archiveChat' => $this->archiveChat,
        ];
    }
}