<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Services\GreenApi\Messaging\MessageInterface;
use App\Services\GreenApi\Messaging\Types\HasContent;

class Media implements Payload
{
    use HasContent;

    public function __construct(
        public string $chatId,
        public string $mediaType,
        public string $media,
        public string $file_name,
        public int    $file_size,
        public string $text = '',
    )
    {
    }

    public function getType(): string
    {
        return $this->mediaType;
    }


    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'media' => $this->media,
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'text' => $this->text,
        ]);
    }
}