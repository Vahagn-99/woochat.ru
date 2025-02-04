<?php

namespace App\Exceptions\AmoChat;

use App\Exceptions\RenderableException;
use App\Exceptions\ReportableException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CreateAmoChatException extends Exception implements ReportableException, RenderableException
{
    public function report(): bool
    {
        do_log('amochat/create-chat')->error($this->getMessage());

        return true;
    }

    public function render(): Response|JsonResponse|bool
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
