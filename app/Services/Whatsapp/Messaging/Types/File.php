<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class File implements IMessage
{
    use Manageable;

    const TYPE = 'fileByUpload';

    public function __construct(
        public readonly string $chatId,
        public readonly string $downloadUrl,
        public readonly string $fileName,
        public readonly ?string $caption = null,
        public readonly ?string $jpegThumbnail = null,
        public readonly ?string $quotedMessageId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'downloadUrl' => $this->downloadUrl,
            'fileName' => $this->fileName,
            'caption' => $this->caption,
            'jpegThumbnail' => $this->jpegThumbnail,
            'quotedMessageId' => $this->quotedMessageId,
        ];
    }
}