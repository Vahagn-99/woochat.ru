<?php

namespace App\Services\GreenApi\QRCode;

interface QRCodeServiceInterface
{
    public function getQRCode(): QRCodeResponseDTO;
}