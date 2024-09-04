<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

interface RendererException
{
    /**
     * Report the exception.
     */
    public function render(): Response|bool;
}
