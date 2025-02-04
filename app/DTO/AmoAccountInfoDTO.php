<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class AmoAccountInfoDTO extends Data
{
    public function __construct(
        public int $id,
        public string $domain,
        public string $name,
        public int $users_count,
        public string $timezone,
        public ?string $tariff = null,
        public ?string $paid_from = null,
        public ?string $paid_till = null,
        public ?string $pay_type = null
    ) {
    }
}