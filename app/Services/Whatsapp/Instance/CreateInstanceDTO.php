<?php

namespace App\Services\Whatsapp\Instance;

use App\DTO\BaseDTO;

class CreateInstanceDTO extends BaseDTO
{
    public function __construct(
        public string $name
    )
    {
    }
}