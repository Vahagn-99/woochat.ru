<?php

namespace App\Services\AmoChat\Chat\Create;

use App\Exceptions\AmoChat\CreateAmoChatException;
use App\Services\AmoChat\Client\ApiClientInterface;
use App\Services\AmoChat\Client\ChatEndpoint;

class ChatService implements ChatServiceInterface
{
    private string $scopeId;

    public function __construct(private readonly ApiClientInterface $apiClient)
    {
    }

    /**
     * @param SaveAmoChatDTO $data
     * @return AmoChat
     *
     * @throws CreateAmoChatException
     * @throws \Exception
     */
    public function create(SaveAmoChatDTO $data): AmoChat
    {
        $url = sprintf(ChatEndpoint::API_CREATE_CHAT_API, $this->scopeId);
        $response = $this->apiClient->request($url, $data->toArray());

        if (isset($response['error'])) {
            throw new CreateAmoChatException($response['error']['message'], $response['error']['code']);
        }

        return AmoChat::fromArray($response);
    }

    public function setScopeId(string $scopeId): static
    {
        $this->scopeId = $scopeId;

        return $this;
    }
}