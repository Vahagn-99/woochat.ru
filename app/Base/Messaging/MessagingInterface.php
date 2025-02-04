<?php

namespace App\Base\Messaging;

use App\Exceptions\Messaging\SendMessageException;

interface MessagingInterface
{
    /**
     * @throws SendMessageException
     * @throws \Exception
     */
    public function send(IMessage $message): SentMessage;

    public function sendStatus(IMessageStatus $message): SentMessageStatus;
}