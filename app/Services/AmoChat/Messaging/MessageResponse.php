<?php

namespace App\Services\AmoChat\Messaging;

class MessageResponse
{
    public function __construct(
        public string $event_type,
        public string $msgId,
        public ?string $ref_id
    )
    {
    }
}