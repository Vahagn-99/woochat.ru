<?php

namespace App\Services\GreenApi\Messaging;

interface MessageInterface
{
    public function getContent(?string $key = null): mixed;

    public function getChatId(): string;

    public function getType(): string;
}