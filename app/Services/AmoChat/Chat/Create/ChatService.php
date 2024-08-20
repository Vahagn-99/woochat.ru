<?php

namespace App\Services\AmoChat\Chat\Create;

use App\Services\AmoChat\Client\ApiClientInterface;
use App\Services\AmoChat\Client\ChatEndpoint;
use Exception;

class ChatService implements ChatServiceInterface
{
    private string $scopeId;

    public function __construct(private readonly ApiClientInterface $apiClient)
    {
    }

    /**
     * @param SaveAmoChatDTO $data
     * @return AmoChat
     * @throws Exception
     */
    public function create(SaveAmoChatDTO $data): AmoChat
    {
        $url = sprintf(ChatEndpoint::API_CREATE_CHAT_API, $this->scopeId);
        $response = $this->apiClient->request($url, $data->toArray());
        return AmoChat::fromArray($response);
    }

    public function setScopeId(string $scopeId): static
    {
        $this->scopeId = $scopeId;
        return $this;
    }
}