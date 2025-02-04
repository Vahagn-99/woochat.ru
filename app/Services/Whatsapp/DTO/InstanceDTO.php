<?php

namespace App\Services\Whatsapp\DTO;

use App\Contracts\FromArray;

final class InstanceDTO implements FromArray
{
    public function __construct(
        public string $id,
        public string $token,
    )
    {
    }

    public static function fromArray(array $params): InstanceDTO
    {
        return new InstanceDTO(
            $params['id'],
            $params['token'],
        );
    }
}