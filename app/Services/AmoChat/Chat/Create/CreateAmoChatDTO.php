<?php

namespace App\Services\AmoChat\Chat\Create;

use App\Contracts\Arrayable;
use App\Services\AmoChat\Messaging\Actor\Actor;

class CreateAmoChatDTO implements Arrayable
{
    public function __construct(
        public string $conversation_id,
        public string $external_id,
        public Actor $sender
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            "conversation_id" => $this->conversation_id,
            "source" => [
                "external_id" => $this->external_id,
            ],
            "user" => $this->sender->toArray(),
        ]);
    }
}