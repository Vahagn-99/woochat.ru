<?php

declare(strict_types=1);

namespace App\DTO\dct;

use Spatie\LaravelData\Dto;

class TariffDTO extends Dto
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
