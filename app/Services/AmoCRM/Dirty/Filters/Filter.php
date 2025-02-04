<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Dirty\Filters;

use Illuminate\Support\Str;

abstract class Filter
{
    public function __construct(public mixed $value, public ?string $name = null)
    {
        $this->validate();
    }

    private function validate(): void
    {
        // берем имя если передано если нет то формируем от имени класса
        $this->name ??= str_replace(['filter', '_'], '', Str::lower(Str::snake(class_basename($this))));

        if (method_exists($this, $validationMethod = Str::camel("validate_{$this->name}"))) {
            $this->{$validationMethod}();
        }
    }
}
