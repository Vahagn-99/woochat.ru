<?php

namespace App\Exceptions\Whatsapp;

use App\Exceptions\RenderableException;
use App\Exceptions\ReportableException;
use Exception;
use Illuminate\Http\Response;

class GetQrCodeException extends Exception implements ReportableException, RenderableException
{
    private mixed $errors;

    public function __construct(string $message, mixed $errors)
    {
        $this->errors = $errors;
        parent::__construct($message, 400);
    }

    public function report(): bool
    {
        do_log('whatsapp/qr')->error($this->getMessage(), [
            'ошибки' => $this->errors,
        ]);

        return false;
    }

    public function render(): Response|bool
    {
        return response($this->errors, $this->code);
    }
}
