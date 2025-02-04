<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class Picture implements IMessage
{
    use Manageable;

    public function __construct(
        public string $mediaType,
        public string $media,
        public string $thumbnail,
        public string $file_name,
        public int    $file_size,
        public string $text = '',
    )
    {
    }

    public function getType(): string
    {
        return "picture";
    }

    /**
     * @throws \App\Exceptions\Messaging\MessageDontHasTypeException
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'media' => $this->media,
            'thumbnail' => $this->thumbnail,
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'text' => $this->text,
        ]);
    }
}