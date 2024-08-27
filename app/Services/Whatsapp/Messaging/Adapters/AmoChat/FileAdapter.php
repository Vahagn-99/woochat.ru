<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\AmoChat\Messaging\Types\File;

class FileAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        $payload = $data['fileMessageData'];

        return new File(media: $payload['downloadUrl'], file_name: $payload['fileName'], text: $payload['fileName']);
    }
}
