<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class BaseDTO implements Arrayable, JsonSerializable
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}