<?php

namespace App\Services\Whatsapp\QRCode;

use GreenApi\RestApi\GreenApiClient;

class QRCodeApi implements QRCodeApiInterface
{
    public function __construct(private readonly GreenApiClient $apiClient)
    {

    }

    public function getQR(): QRCodeResponseDTO
    {
        $response = $this->apiClient->account->qr();
        return new QRCodeResponseDTO(
            $response->data->type,
            $response->data->message
        );
    }
}