<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Services\GreenApi\Messaging\Types\HasContent;

class Contact implements Payload
{
    use HasContent;

    public function __construct(
        public string $chatId,
        public string $name,
        public string $phone,
        public string $text = '',
    )
    {
    }

    public function getType(): string
    {
        return 'contact';
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'name' => $this->name,
            'phone' => $this->phone,
            'text' => $this->text,
        ]);
    }
}