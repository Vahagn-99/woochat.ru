<?php

namespace App\Services\AmoChat\Messaging;

use App\Services\AmoChat\Client\ApiClientInterface;
use App\Services\AmoChat\Client\ChatEndpoint;
use App\Services\GreenApi\Messaging\MessageInterface;
use Exception;

class MessageService implements MessageServiceInterface
{
    private string $scopeId;

    public function __construct(private readonly ApiClientInterface $apiClient)
    {
    }

    public function setScopeId(string $scopeId): void
    {
        $this->scopeId = $scopeId;
    }

    /**
     * @throws Exception
     */
    public function send(MessageInterface $message): MessageResponse
    {
        $url = sprintf(ChatEndpoint::API_SEND_MESSAGE_API, $this->scopeId);
        $response = $this->apiClient->request($url, $message->toArray());
        $eventType = array_key_first($response);

        return new MessageResponse(
            $eventType,
            $response[$eventType]['msgid'],
            $response[$eventType]['ref_id']
        );
    }
}