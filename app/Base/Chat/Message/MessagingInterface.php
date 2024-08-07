<?php

namespace App\Base\Chat\Message;

interface MessagingInterface
{
    public function send(IMessage $message): Response;
}