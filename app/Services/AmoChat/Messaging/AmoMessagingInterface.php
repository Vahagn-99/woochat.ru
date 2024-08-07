<?php

namespace App\Services\AmoChat\Messaging;

use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\Response;
use App\Base\Chat\Message\MessagingInterface;

interface AmoMessagingInterface extends MessagingInterface
{
    public function setScopeId(string $scopeId): static;

    public function send(IMessage $message): Response;
}