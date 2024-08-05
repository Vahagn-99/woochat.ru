<?php

namespace App\Services\GreenApi\Messaging\Send;

use App\Services\GreenApi\Messaging\MessageId;
use App\Services\GreenApi\Messaging\MessageInterface;
use GreenApi\RestApi\GreenApiClient;

class SendMessageService implements SendMessageServiceInterface
{
    public function __construct(private readonly GreenApiClient $greenApiClient)
    {
    }

    public function forwardMessages(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->forwardMessages();
        return new MessageId($response->idMessage);
    }

    public function sendButtons(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendButtons();
        return new MessageId($response->idMessage);
    }

    public function sendContact(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendContact();
        return new MessageId($response->idMessage);
    }

    public function sendFileByUpload(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendFileByUpload();
        return new MessageId($response->idMessage);
    }

    public function sendFileByUrl(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendFileByUrl();
        return new MessageId($response->idMessage);
    }

    public function sendLink(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->MessageInterface();
        return new MessageId($response->idMessage);
    }

    public function sendMessage(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendMessage(
            $message->getChatId(),
            $message->getContent('message')
        );

        return new MessageId($response->idMessage);
    }

    public function sendLocation(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendLocation();
        return new MessageId($response->idMessage);
    }

    public function sendListMessage(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendListMessage();
        return new MessageId($response->idMessage);
    }

    public function sendTemplateButtons(MessageInterface $message): MessageId
    {
        $response = $this->greenApiClient->sending->sendTemplateButtons();
        return new MessageId($response->idMessage);
    }
}