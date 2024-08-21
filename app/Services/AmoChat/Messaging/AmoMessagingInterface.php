<?php

namespace App\Services\AmoChat\Messaging;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\MessagingInterface;
use App\Base\Messaging\SentMessage;

interface AmoMessagingInterface extends MessagingInterface
{
    public function setScopeId(string $scopeId): static;

    public function getLastRequestInfo(): null|object;

    public function send(IMessage $message): SentMessage;
}