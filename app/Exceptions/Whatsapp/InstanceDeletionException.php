<?php

namespace App\Exceptions\Whatsapp;

use App\Exceptions\ReportableException;
use Exception;

class InstanceDeletionException extends Exception implements ReportableException
{
    private array $errors;

    public function __construct(string $message, int $code = 400, array $errors = [])
    {
        $this->errors = $errors;

        parent::__construct($message, $code);
    }

    public function report(): bool
    {
        do_log("whatsapp/instances")->error($this->getMessage(), [
            'ошибки' => $this->errors,
        ]);

        return false;
    }
}
