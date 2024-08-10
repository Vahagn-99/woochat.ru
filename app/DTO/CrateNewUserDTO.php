<?php

namespace App\DTO;

class CrateNewUserDTO
{
    public function __construct(
        public int  $id,
        public string  $amojo_id,
        public string  $domain,
        public ?string $email = null,
        public ?string $phone = null,
    )
    {
    }
}