<?php

namespace App\Services\GreenApi\Messaging\Send;

use App\Services\GreenApi\Messaging\MessageId;
use App\Services\GreenApi\Messaging\MessageInterface;

interface SendMessageServiceInterface
{
    public function forwardMessages(MessageInterface $message): MessageId;

    public function sendButtons(MessageInterface $message): MessageId;

    public function sendContact(MessageInterface $message): MessageId;

    public function sendFileByUpload(MessageInterface $message): MessageId;

    public function sendFileByUrl(MessageInterface $message): MessageId;

    public function sendLink(MessageInterface $message): MessageId;

    public function sendMessage(MessageInterface $message): MessageId;

    public function sendLocation(MessageInterface $message): MessageId;

    public function sendListMessage(MessageInterface $message): MessageId;

    public function sendTemplateButtons(MessageInterface $message): MessageId;
}