<?php

namespace App\Services\GreenApi\Instance;

use App\DTO\BaseDTO;

class CreateInstanceDTO extends BaseDTO
{
    public function __construct(
        public string $name
    )
    {
    }
}