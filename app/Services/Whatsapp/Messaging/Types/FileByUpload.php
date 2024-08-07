<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

class FileByUpload implements IMessage
{
    use Manageable;

    const TYPE = 'fileByUpload';

    public function __construct(
        public string  $chatId,
        public string  $path,
        public ?string $fileName = null,
        public ?string $caption = null,
        public ?string $quotedMessageId = null
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'path' => $this->path,
            'fileName' => $this->fileName,
            'caption' => $this->caption,
            'quotedMessageId' => $this->quotedMessageId,
        ];
    }
}