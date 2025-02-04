<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Dirty\Filters;

class Email extends Filter
{
    protected function validateEmail(): void
    {
        $this->value = urlencode($this->value);
    }
}
