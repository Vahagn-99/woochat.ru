<?php

namespace App\Services\AmoChat\Chat\Connect;

use App\Services\AmoChat\Client\ApiClientInterface;
use App\Services\AmoChat\Client\ChatEndpoint;
use Exception;

class ConnectChatService implements ConnectChatServiceInterface
{
    protected array $body = [];
    protected string $channelId;

    public function __construct(private readonly ApiClientInterface $apiClient)
    {
        $this->body = [
            'hook_api_version' => config('amochat.hook_api_version'),
            'title' => config('amochat.name')
        ];

        $this->channelId = config('amochat.id');
    }

    /**
     * @throws Exception
     */
    public function connect($accountId): Connetion
    {
        $this->body['account_id'] = $accountId;
        $url = sprintf(ChatEndpoint::API_CONNECT_CHAT_API, $this->channelId);
        $response = $this->apiClient->request($url, $this->body);
        return Connetion::fromArray($response);
    }
}
