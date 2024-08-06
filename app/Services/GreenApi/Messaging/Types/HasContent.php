<?php

namespace App\Services\GreenApi\Messaging\Types;

use Illuminate\Support\Arr;

trait HasContent
{
    public function getChatId(): string
    {
        return $this->chatId;
    }

    public function getContent(?string $key = null): mixed
    {
        $data = array_filter(get_object_vars($this));

        if ($key) {
            return Arr::get($data, $key);
        }

        return $data;
    }
}