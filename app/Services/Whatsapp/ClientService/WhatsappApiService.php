<?php

namespace App\Services\Whatsapp\ClientService;

use GreenApi\RestApi\GreenApiClient;

class WhatsappApiService implements WhatsappApiServiceInterface
{
    public function __construct(private readonly GreenApiClient $greenApiClient)
    {
    }

    public function getClient(): GreenApiClient
    {
        return $this->greenApiClient;
    }
}