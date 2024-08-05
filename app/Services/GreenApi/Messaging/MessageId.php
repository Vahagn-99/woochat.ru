<?php

namespace App\Services\GreenApi\Messaging;

class MessageId
{
    public function __construct(
        public string $messageId
    )
    {
    }
}