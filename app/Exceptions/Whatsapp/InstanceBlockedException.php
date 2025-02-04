<?php

namespace App\Exceptions\Whatsapp;

use App\Exceptions\RenderableException;
use App\Exceptions\ReportableException;
use Exception;
use Illuminate\Http\JsonResponse;

class InstanceBlockedException extends Exception implements ReportableException, RenderableException
{
    public function report(): bool
    {
        do_log('instance')->error($this->getMessage());

        return false;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
