<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class FileByUrl implements IMessage
{
    use Manageable;

    const TYPE = 'fileByUrl';

    public function __construct(
        public string  $chatId,
        public string  $urlFile,
        public ?string $fileName = null,
        public ?string $caption = null,
        public ?string $quotedMessageId = null,
        public bool    $archiveChat = false
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'urlFile' => $this->urlFile,
            'fileName' => $this->fileName,
            'caption' => $this->caption,
            'quotedMessageId' => $this->quotedMessageId,
            'archiveChat' => $this->archiveChat,
        ];
    }
}