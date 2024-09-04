<?php

namespace App\Services\Whatsapp\QRCode;

class QRCodeService implements QRCodeServiceInterface
{
    public function __construct(private readonly QRCodeApiInterface $api)
    {
    }

    /**
     * @throws \App\Exceptions\Whatsapp\GetQrCodeException
     */
    public function getQRCode(): QRCodeResponseDTO
    {
        return $this->api->getQR();
    }
}