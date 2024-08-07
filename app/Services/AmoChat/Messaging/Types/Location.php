<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Chat\Message\Manageable;

class Location implements Payload
{
    use Manageable;

    public function __construct(
        public string $chatId,
        public string $lon,
        public string $lat,
        public string $text = '',
    )
    {
    }

    public function getType(): string
    {
        return 'location';
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->getType(),
            'lon' => $this->lon,
            'lat' => $this->lat,
            'text' => $this->text,
        ]);
    }
}