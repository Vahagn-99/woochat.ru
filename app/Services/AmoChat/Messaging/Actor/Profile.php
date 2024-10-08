<?php

namespace App\Services\AmoChat\Messaging\Actor;

use App\Contracts\Arrayable;
use Illuminate\Support\Str;

class Profile implements Arrayable
{
    public function __construct(
        public string $phone,
        public ?string $email = null)
    {
    }

    public function toArray(): array
    {
        return array_filter([
            'phone' => $this->formatPhone($this->phone),
            'email' => $this->email,
        ]);
    }

    private function formatPhone(string $phone): string
    {
        $cleaned_phone = Str::replace('+', '', Str::upper($phone));

        return '+'.$cleaned_phone;
    }
}
