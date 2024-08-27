<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class Media implements IMessage
{
    use Manageable;

    public function __construct(
        public string $mediaType,
        public string $media,
        public string $file_name,
        public string $text = '',
        public ?int $file_size = null,
    ) {
    }

    public function getType(): string
    {
        return $this->mediaType;
    }

    /**
     * @throws \App\Exceptions\Messaging\MessageDontHasTypeException
     */
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