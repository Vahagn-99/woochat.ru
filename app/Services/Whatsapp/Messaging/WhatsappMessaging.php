<?php

namespace App\Services\Whatsapp\Messaging;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\IMessageStatus;
use App\Base\Messaging\SentMessage;
use App\Base\Messaging\SentMessageStatus;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\Str;

class WhatsappMessaging implements WhatsappMessagingInterface
{
    public function __construct(private readonly GreenApiClient $apiClient)
    {

    }

    public function send(IMessage $message): SentMessage
    {
        $messageToArray = $message->toArray();

        $method = $this->getSendingMethodByMessageType($message->getType());
        $resp = $this->apiClient->sending->{$method}(...$messageToArray);

        return new SentMessage(id: $resp->data->idMessage);
    }

    private function getSendingMethodByMessageType(string $messageType): string
    {
        return Str::camel("send_".$messageType);
    }

    public function sendStatus(IMessageStatus $message): SentMessageStatus
    {
        return new SentMessageStatus(
            id: $message->getId(),
            status: $message->getStatus(),
        );
    }
}