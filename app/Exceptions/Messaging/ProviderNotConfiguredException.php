<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class ProviderNotConfiguredException extends Exception implements ReportableException
{
    public function __construct(private readonly string $provider)
    {
        parent::__construct("Провайдер '{$this->provider}' не настроен.");
    }

    public function report(): bool
    {
        do_log("messaging/providers")->error("Провайдер {$this->provider} не настроен.");

        return false;
    }
}
