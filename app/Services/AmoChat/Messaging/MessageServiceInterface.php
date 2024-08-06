<?php

namespace App\Services\AmoChat\Messaging;

use App\Services\GreenApi\Messaging\MessageInterface;

interface MessageServiceInterface
{
    public function setScopeId(string $scopeId): void;

    public function send(MessageInterface $message): MessageResponse;
}