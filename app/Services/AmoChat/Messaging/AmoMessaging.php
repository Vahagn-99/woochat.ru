<?php

namespace App\Services\AmoChat\Messaging;

use App\Base\Messaging\IMessage;
use App\Base\Messaging\IMessageStatus;
use App\Base\Messaging\SentMessage;
use App\Base\Messaging\SentMessageStatus;
use App\Exceptions\Messaging\SendMessageException;
use App\Exceptions\Messaging\UpdateMessageDeliveryStatusException;
use App\Services\AmoChat\Client\ApiClientInterface;
use App\Services\AmoChat\Client\ChatEndpoint;
use Exception;

class AmoMessaging implements AmoMessagingInterface
{
    private string $scopeId;

    /**
     * @param \App\Services\AmoChat\Client\ApiClientInterface $apiClient
     */
    public function __construct(private readonly ApiClientInterface $apiClient)
    {
    }

    /**
     * @param string $scopeId
     * @return $this
     */
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
        $response = $this->request(ChatEndpoint::API_SEND_MESSAGE_API, $message->toArray());

        if (isset($response['error'])) {
            throw new SendMessageException('amochat', $response['error']['message'], $response['error']['errors']);
        }

        $eventType = array_key_first($response);

        return new SentMessage(id: $response[$eventType]['msgid'], ref_id: $response[$eventType]['ref_id']);
    }

    /**
     * @throws \Exception
     */
    public function sendStatus(IMessageStatus $message): SentMessageStatus
    {
        try {
            $response = $this->request(ChatEndpoint::API_SEND_MESSAGE_STATUS_API, $message->toArray());

            if (isset($response['errors'])) {
                throw new UpdateMessageDeliveryStatusException('amochat', $response['errors']);
            }

            return new SentMessageStatus(id: $message->getId(), status: $message->getStatus());
        } catch (Exception $e) {
            throw new UpdateMessageDeliveryStatusException('amochat', $e->getMessage());
        }
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return array
     *
     * @throws \Exception
     */
    protected function request(string $endpoint, array $data, string $method = 'POST'): array
    {
        $url = sprintf($endpoint, $this->scopeId);

        return $this->apiClient->request($url, $data, $method);
    }

    /**
     * @return array
     */
    public function getLastRequestInfo(): array
    {
        return $this->apiClient->getLastRequestInfo();
    }
}