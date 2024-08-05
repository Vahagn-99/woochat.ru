<?php

namespace App\Services\GreenApi\Messaging;

interface MessagingServiceInterface
{
    public function send(MessageInterface $message): MessageId;
}