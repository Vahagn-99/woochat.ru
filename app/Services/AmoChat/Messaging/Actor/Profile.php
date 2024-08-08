<?php

namespace App\Services\AmoChat\Messaging\Actor;

use App\Contracts\Arrayable;

class Profile implements Arrayable
{
    public function __construct(
        public string $phone,
        public ?string $email = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'phone' => $this->phone,
            'email' => $this->email,
        ]);
    }
}