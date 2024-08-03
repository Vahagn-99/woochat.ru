<?php

namespace App\Services\GreenApi\QRCode;

class QRCodeManager implements QRCodeManagerInterface
{
    public function __construct(private readonly QRCodeApiInterface $api)
    {
    }

    public function getQRCode(): QRCodeResponseDTO
    {
        return $this->api->getQR();
    }
}