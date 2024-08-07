<?php

namespace App\Services\Whatsapp\ClientService;

use GreenApi\RestApi\GreenApiClient;

interface WhatsappApiServiceInterface
{
    public function getClient(): GreenApiClient;
}