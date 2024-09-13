<?php

namespace App\Exceptions\Whatsapp;

use App\Exceptions\RenderableException;
use Exception;
use Illuminate\Http\JsonResponse;

class InstanceNotFoundException extends Exception implements RenderableException
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
