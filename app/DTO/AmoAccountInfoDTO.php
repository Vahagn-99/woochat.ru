<?php

namespace App\DTO;

class AmoAccountInfoDTO
{
    public function __construct(
        public int    $id,
        public string $domain,
        public int    $users_count,
        public string $paid_from,
        public string $paid_till,
        public string $pay_type,
        public string $timezone,
        public string $tariff
    )
    {
    }
}