<?php

namespace App\Services\Whatsapp\QRCode;

class QRCodeService implements QRCodeServiceInterface
{
    public function __construct(private readonly QRCodeApiInterface $api)
    {
    }

    public function getQRCode(): QRCodeResponseDTO
    {
        return $this->api->getQR();
    }
}