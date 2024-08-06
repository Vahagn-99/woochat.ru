<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Services\GreenApi\Messaging\Types\HasContent;

class Text implements Payload
{
    use HasContent;

    public function __construct(
        public string  $chatId,
        public string  $text,
        public ?string $sticker_id = null,
    )
    {
    }

    public function getType(): string
    {
        return 'text';
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'text' => $this->text,
            'sticker_id' => $this->sticker_id,
        ]);
    }
}