<?php

namespace App\Services\Whatsapp\QRCode;

interface QRCodeApiInterface
{
    /**
     * @throws \App\Exceptions\Whatsapp\GetQrCodeException
     */
    public function getQR(): QRCodeResponseDTO;
}