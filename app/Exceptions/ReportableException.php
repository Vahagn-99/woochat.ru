<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ReportableException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): bool
    {
        return true;
    }
}
