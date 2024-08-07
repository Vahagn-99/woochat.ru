<?php

namespace App\Contracts;

interface FromArray
{
    public static function fromArray(array $params): mixed;
}