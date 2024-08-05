<?php

namespace App\Services\GreenApi\Messaging;

use App\Services\GreenApi\Messaging\Send\SendMessageServiceInterface;

class MessagingService implements MessagingServiceInterface
{
    public function __construct(private readonly SendMessageServiceInterface $sending)
    {
    }

    public function send(MessageInterface $message): MessageId
    {
        return $this->sending->{$message->getType()}($message);
    }
}