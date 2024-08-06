<?php

namespace App\Services\GreenApi\Messaging;

use App\Contracts\Arrayable;

interface MessageInterface extends Arrayable
{
    public function getContent(?string $key = null): mixed;

    public function getChatId(): string;

    public function getType(): string;
}