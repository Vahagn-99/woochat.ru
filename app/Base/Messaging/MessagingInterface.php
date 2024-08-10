<?php

namespace App\Base\Messaging;

interface MessagingInterface
{
    public function send(IMessage $message): SentMessage;
}