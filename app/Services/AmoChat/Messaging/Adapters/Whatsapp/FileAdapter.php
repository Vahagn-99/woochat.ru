<?php

declare(strict_types=1);

namespace App\Services\AmoChat\Messaging\Adapters\Whatsapp;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\Whatsapp\Messaging\Types\File;

class FileAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        $chatId = $data['receiver']['client_id'] ?? $data['sender']['phone']."@c.us";
        $payload = $data['message'];
        return new File(
            chatId: $chatId,
            downloadUrl: $payload['media'],
            fileName: $payload['file_name'],
            caption: $payload['text'],
            jpegThumbnail: $payload['thumbnail'],
        );
    }
}
