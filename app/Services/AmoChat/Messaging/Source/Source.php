<?php

namespace App\Services\AmoChat\Messaging\Source;

use App\Contracts\Arrayable;

class Source implements Arrayable
{
    public function __construct(
        public string $id,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'external_id' => $this->id,
        ]);
    }
}