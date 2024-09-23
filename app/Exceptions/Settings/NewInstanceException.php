<?php

namespace App\Exceptions\Settings;

use App\Exceptions\RenderableException;
use App\Exceptions\ReportableException;
use Exception;
use Illuminate\Http\JsonResponse;

class NewInstanceException extends Exception implements ReportableException, RenderableException
{
    public static function limitOver($current_count): static
    {
        return new static("Вы не можете создать больше {$current_count} инстансов.",403);
    }

    public function report(): bool
    {
        do_log('settings')->error($this->getMessage());

        return false;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
