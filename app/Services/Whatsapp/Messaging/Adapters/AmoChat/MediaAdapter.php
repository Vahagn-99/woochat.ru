<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\AmoChat\Messaging\Types\Media;
use Illuminate\Support\Str;

class MediaAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        $payload = $data['fileMessageData'];
        $mediaType = Str::before($payload['mimeType'], '/');

        return new Media(
            mediaType: $mediaType,
            media: $payload['downloadUrl'],
            file_name: $payload['fileName'],
            text: $payload['fileName']
        );
    }
}
