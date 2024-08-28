<?php

declare(strict_types=1);

namespace App\Exceptions;

interface ReportableException
{
    /**
     * Report the exception.
     */
    public function report(): bool;
}
