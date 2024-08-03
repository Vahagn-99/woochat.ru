<?php

namespace App\Services\GreenApi\QRCode;

interface QRCodeManagerInterface
{
    public function getQRCode(): QRCodeResponseDTO;
}