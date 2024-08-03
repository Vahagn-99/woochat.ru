<?php

namespace App\Services\GreenApi\QRCode;

interface QRCodeApiInterface
{
    public function getQR(): QRCodeResponseDTO;
}