<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class Location implements IMessage
{
    use Manageable;

    const TYPE = 'location';

    public function __construct(
        public string  $chatId,
        public float   $latitude,
        public float   $longitude,
        public ?string $nameLocation = null,
        public ?string $address = null,
        public ?string $quotedMessageId = null
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'nameLocation' => $this->nameLocation,
            'address' => $this->address,
            'quotedMessageId' => $this->quotedMessageId,
        ];
    }
}