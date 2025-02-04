<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class SendMessageException extends Exception implements ReportableException
{
    private array $errors;

    private array $body;

    private string $provider;

    public function __construct(string $provider, string $message, array $errors = [], array $body = [])
    {
        $this->errors = $errors;
        $this->provider = $provider;
        $this->body = $body;

        parent::__construct($message, 400);
    }

    public function report(): bool
    {
        do_log("messaging/{$this->provider}")->error($this->getMessage(), [
            'ошибки' => $this->errors,
            'запрось' => $this->body,
        ]);

        return false;
    }
}
