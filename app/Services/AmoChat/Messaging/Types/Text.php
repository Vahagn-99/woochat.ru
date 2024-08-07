<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Chat\Message\Manageable;

class Text implements Payload
{
    use Manageable;

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