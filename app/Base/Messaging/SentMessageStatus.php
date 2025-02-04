<?php

namespace App\Base\Messaging;

class SentMessageStatus
{
    public function __construct(
        public string $id,
        public string $status
    ) {
    }
}