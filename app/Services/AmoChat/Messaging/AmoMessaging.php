<?php

namespace App\Services\AmoChat\Messaging;

use App\Base\Chat\Message\EventType;
use App\Base\Chat\Message\IMessage;
use App\Base\Chat\Message\MessageId;
use App\Base\Chat\Message\Response;
use App\Services\AmoChat\Client\ApiClientInterface;
use App\Services\AmoChat\Client\ChatEndpoint;
use Exception;

class AmoMessaging implements AmoMessagingInterface
{
    private string $scopeId;

    public function __construct(private readonly ApiClientInterface $apiClient)
    {
    }

    public function setScopeId(string $scopeId): static
    {
        $this->scopeId = $scopeId;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(IMessage $message): Response
    {
        $url = sprintf(ChatEndpoint::API_SEND_MESSAGE_API, $this->scopeId);
        $response = $this->apiClient->request($url, $message->toArray());
        $eventType = array_key_first($response);

        return new Response(
            event_type: new EventType($eventType),
            id: new MessageId($response[$eventType]['msgid']),
            ref_id: isset($response[$eventType]['ref_id']) ? new MessageId($response[$eventType]['ref_id']) : null,
        );
    }
}