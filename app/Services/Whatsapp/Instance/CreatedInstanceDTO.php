<?php

namespace App\Services\Whatsapp\Instance;

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