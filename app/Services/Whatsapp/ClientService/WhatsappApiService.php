<?php

namespace App\Services\Whatsapp\ClientService;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class WhatsappApiService implements WhatsappApiServiceInterface
{
    public function __construct(private readonly GreenApiClient $greenApiClient)
    {
    }

    public function getClient(): GreenApiClient
    {
        return $this->greenApiClient;
    }

    public function clearQueue(): stdClass
    {
        return $this->greenApiClient->queues->clearMessagesQueue();
    }
}