<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Manageable;

class ForwardMessages implements IMessage
{
    use Manageable;

    const TYPE = 'location';

    public function __construct(
        public string $chatId,
        public string $chatIdFrom,
        public array  $messages
    )
    {
    }

    public function toArray(): array
    {
        return [
            'chatId' => $this->chatId,
            'chatIdFrom' => $this->chatIdFrom,
            'messages' => $this->messages,
        ];
    }
}