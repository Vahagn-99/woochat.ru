<?php

namespace App\Services\AmoChat\Chat\Create;

use App\Contracts\Arrayable;
use App\Services\AmoChat\Messaging\Actor\Actor;

class SaveAmoChatDTO implements Arrayable
{
    public function __construct(
        public string $conversation_id,
        public Actor $sender,
        public ?Source $source = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            "conversation_id" => $this->conversation_id,
            "user" => $this->sender->toArray(),
        ]);
    }
}