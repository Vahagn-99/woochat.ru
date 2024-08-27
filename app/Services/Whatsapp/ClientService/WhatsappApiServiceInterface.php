<?php

namespace App\Services\Whatsapp\ClientService;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

interface WhatsappApiServiceInterface
{
    public function getClient(): GreenApiClient;

    public function clearQueue():   stdClass;
}