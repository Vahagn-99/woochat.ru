<?php

namespace App\Services\Whatsapp\QRCode;

interface QRCodeApiInterface
{
    public function getQR(): QRCodeResponseDTO;
}