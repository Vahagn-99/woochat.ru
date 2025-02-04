<?php

namespace App\Services\AmoChat\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class Location implements IMessage
{
    use Manageable;

    public function __construct(
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