<?php

namespace App\Services\GreenApi\QRCode;

use App\DTO\BaseDTO;

class QRCodeResponseDTO extends BaseDTO
{
    public function __construct(
        public string $type,
        public string $message
    )
    {
    }
}