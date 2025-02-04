<?php

namespace App\Services\AmoChat\Messaging\Actor;

use App\Contracts\Arrayable;

class Actor implements Arrayable
{
    public function __construct(
        public string $id,
        public string $name,
        public Profile $profile,
        public ?string $avatar = null,
        public ?string $ref_id = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => $this->name,
            'ref_id' => $this->ref_id,
            'profile' => $this->profile->toArray(),
        ]);
    }
}