<?php

namespace App\Services\Whatsapp\Messaging;

use App\Base\Chat\Message\EventType;
use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\MessageId;
use App\Base\Chat\Message\Response;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Support\Str;

class WhatsappMessaging implements WhatsappMessagingInterface
{
    public function __construct(private readonly GreenApiClient $apiClient)
    {

    }

    public function send(IMessage $message): Response
    {
        $messageToArray = $message->toArray();
        $method = $this->getSendingMethodByMessageType($message->getType());

        $resp = $this->apiClient->sending->{$method}(...$messageToArray);

        return new Response(
            new EventType("new message"),
            new MessageId($resp->data->idMessage)
        );
    }

    private function getSendingMethodByMessageType(string $messageType): string
    {
        return Str::camel("send_" . $messageType);
    }
}