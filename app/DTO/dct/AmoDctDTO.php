<?php

declare(strict_types=1);

namespace App\DTO\dct;

use Spatie\LaravelData\Dto;

class AmoDctDTO extends Dto
{
    public function __construct(
        public array $tariffs,
        public int $pipeline_id,
        public int $status_id,
        public int $responsible_user_id,
        public int $tariff_id,
        public int $account_id,
        public int $user_count_id,
        public int $paid_till_id,
    ) {
    }

    public static function make(): AmoDctDTO
    {
        $config = config('amocrm-dct');

        $tariffs = [];
        foreach ($config['tariffs'] as $id => $name) {
            $tariffs[] = new TariffDTO((string)$id, $name);
        }

        return new AmoDctDTO(
            $tariffs,
            $config['pipeline_id'],
            $config['status_id'],
            $config['responsible_user_id'],
            $config['tariff_id'],
            $config['account_id'],
            $config['user_count_id'],
            $config['paid_till_id'],
        );
    }
}
