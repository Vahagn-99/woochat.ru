<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Dirty\Filters;

class Phone extends Filter
{
    protected function validatePhone(): void
    {
        $this->value = str_replace('(', '', $this->value);
        $this->value = str_replace(')', '', $this->value);
        $this->value = str_replace('-', '', $this->value);
        $this->value = str_replace(' ', '', $this->value);

        $this->value = substr($this->value, 3);
    }
}
