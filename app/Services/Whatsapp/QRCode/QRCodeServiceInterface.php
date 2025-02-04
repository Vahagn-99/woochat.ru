<?php

namespace App\Services\Whatsapp\QRCode;

interface QRCodeServiceInterface
{
    /**
     * @throws \App\Exceptions\Whatsapp\GetQrCodeException
     */
    public function getQRCode(): QRCodeResponseDTO;
}