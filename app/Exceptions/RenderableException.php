<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

interface RenderableException
{
    /**
     * Report the exception.
     */
    public function render(): Response|JsonResponse|bool;
}
