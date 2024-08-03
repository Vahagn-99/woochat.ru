<?php

namespace App\Services\GreenApi\DTO;

class InstanceDTO
{
    public function __construct(
        public string $id,
        public string $token,
    )
    {
    }
}