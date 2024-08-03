<?php

namespace App\Services\GreenApi\Instance;

class CreatedInstanceDTO
{
    public function __construct(
        public string $id,
        public string $token,
        public string $type

    )
    {
    }
}