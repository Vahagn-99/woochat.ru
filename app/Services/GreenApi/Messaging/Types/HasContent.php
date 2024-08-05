<?php

namespace App\Services\GreenApi\Messaging\Types;

use Illuminate\Support\Arr;
use ReflectionClass;

trait HasContent
{
    public function getContent(?string $key = null): mixed
    {
        $properties = (new ReflectionClass($this))->getProperties();

        $data = array_filter($properties);

        if ($key) {
            return Arr::get($data, $key);
        }

        return $data;
    }
}