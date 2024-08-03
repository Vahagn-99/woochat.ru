<?php

namespace App\Services\GreenApi\ClientService;

use GreenApi\RestApi\GreenApiClient;

class GreenApiService implements GreenApiServiceInterface
{
    public function __construct(private readonly GreenApiClient $greenApiClient)
    {
    }

    public function getClient(): GreenApiClient
    {
        return $this->greenApiClient;
    }
}