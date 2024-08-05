<?php

namespace App\Services\GreenApi\Messaging\Types;

use Illuminate\Support\Arr;
use ReflectionClass;

trait HasContent
{
    public function getContent(?string $key = null): mixed
    {
        $data = array_filter(get_object_vars($this));

        if ($key) {
            return Arr::get($data, $key);
        }

        return $data;
    }
}