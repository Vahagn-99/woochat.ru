<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class UpdateMessageDeliveryStatusException extends Exception implements ReportableException
{
    private array $errors;

    private string $provider;

    public function __construct(string $provider, string $message, mixed $errors = [])
    {
        $this->errors = $errors;
        $this->provider = $provider;

        parent::__construct($message, 400);
    }

    public function report(): bool
    {
        do_log("messaging/delivery-statuses")->error($this->getMessage(), [
            'ошибки' => $this->errors,
        ]);

        return false;
    }
}
