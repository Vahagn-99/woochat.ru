<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class AmoAccountInfoDTO extends Data
{
    public function __construct(
        public int    $id,
        public string $domain,
        public string $name,
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