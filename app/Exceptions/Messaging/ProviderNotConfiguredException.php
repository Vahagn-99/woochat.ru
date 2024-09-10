<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class ProviderNotConfiguredException extends Exception implements ReportableException
{
    public function __construct(private readonly string $provider)
    {
        parent::__construct("Provider '{$this->provider}' not configured");
    }

    public function report(): bool
    {
        do_log("messaging/providers")->error("The provider {$this->provider} is not configured.");

        return false;
    }
}
