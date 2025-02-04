<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class File implements IMessage
{
    use Manageable;

    const TYPE = 'fileByUrl';

    public function __construct(
        public readonly string $chatId,
        public readonly string $urlFile,
        public readonly ?string $fileName = null,
        public readonly ?string $caption = null,
        public readonly ?string $quotedMessageId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'urlFile' => $this->urlFile,
            'fileName' => $this->fileName,
            'caption' => $this->caption,
            'quotedMessageId' => $this->quotedMessageId,
        ];
    }
}