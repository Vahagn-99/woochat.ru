<?php

namespace App\Services\Whatsapp\Messaging\Types;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\Manageable;

class ForwardMessages implements IMessage
{
    use Manageable;

    const TYPE = 'forwardMessages';

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