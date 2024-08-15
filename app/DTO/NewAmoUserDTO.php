<?php

namespace App\DTO;

class NewAmoUserDTO
{
    public function __construct(
        public int $id,
        public string $api_key,
        public string $amojo_id,
        public string $domain,
        public ?string $email = null,
        public ?string $phone = null,
    ) {
    }
}