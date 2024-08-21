<?php

namespace App\Services\AmoChat\Messaging;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\SentMessage;
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
    public function send(IMessage $message): SentMessage
    {
        $url = sprintf(ChatEndpoint::API_SEND_MESSAGE_API, $this->scopeId);
        $response = $this->apiClient->request($url, $message->toArray());
        $eventType = array_key_first($response);

        return new SentMessage(id: $response[$eventType]['msgid'], ref_id: $response[$eventType]['ref_id']);
    }

    public function getLastRequestInfo(): array
    {
        return $this->apiClient->getLastRequestInfo();
    }
}