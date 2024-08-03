<?php

namespace App\Services\GreenApi\ClientService;

use GreenApi\RestApi\GreenApiClient;

interface GreenApiServiceInterface
{
    public function getClient(): GreenApiClient;
}