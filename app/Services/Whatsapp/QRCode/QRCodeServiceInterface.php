<?php

namespace App\Services\Whatsapp\QRCode;

interface QRCodeServiceInterface
{
    public function getQRCode(): QRCodeResponseDTO;
}