<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportableException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): bool
    {
        return true;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): ResponseFactory|Application|Response
    {
        return response(/* ... */);
    }
}
