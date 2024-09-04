<?php

namespace App\Services\Whatsapp\QRCode;

use App\Exceptions\Whatsapp\GetQrCodeException;
use GreenApi\RestApi\GreenApiClient;

class QRCodeApi implements QRCodeApiInterface
{
    public function __construct(private readonly GreenApiClient $apiClient)
    {

    }

    /**
     * @throws \App\Exceptions\Whatsapp\GetQrCodeException
     */
    public function getQR(): QRCodeResponseDTO
    {
        $response = $this->apiClient->account->qr();

        if (isset($response->error)) {
            throw new GetQrCodeException("Не удалесь получить qr код, попробуйте еще раз!", $response->error);
        }

        return new QRCodeResponseDTO($response->data->type, $response->data->message);
    }
}