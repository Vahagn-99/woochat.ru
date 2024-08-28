<?php

declare(strict_types=1);

namespace App\Services\Whatsapp\Messaging\Adapters\AmoChat;

use App\Base\Messaging\Adapter;
use App\Base\Messaging\IMessage;
use App\Services\AmoChat\Messaging\Types\Media;

abstract class MediaAdapter implements Adapter
{
    public function adapt(array $data): IMessage
    {
        $payload = $data['fileMessageData'];
        $mediaType = $this->mediaType();

        return new Media(mediaType: $mediaType, media: $payload['downloadUrl'], text: $payload['caption'] ?? $payload['text'] ?? '');
    }

    abstract protected function mediaType(): string;
}
