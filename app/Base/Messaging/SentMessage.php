<?php

namespace App\Base\Messaging;

class SentMessage
{
    public function __construct(
        public string $id,
        public ?string $ref_id = null
    ) {
    }
}