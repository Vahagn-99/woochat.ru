<?php

namespace App\Exceptions\AmoChat;

use App\Exceptions\RenderableException;
use App\Exceptions\ReportableException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GivenScopeNotFoundException extends Exception implements RenderableException, ReportableException
{
    public function render(): Response|JsonResponse|bool
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }

    public function report(): bool
    {

        do_log("amocrm/scopes")->warning($this->getMessage());

        return false;
    }
}
