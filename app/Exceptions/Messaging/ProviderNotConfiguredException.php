<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;

class ProviderNotConfiguredException extends ReportableException
{
    public function __construct(private readonly string $provider)
    {
        parent::__construct("Provider '{$this->provider}' not configured");
    }

    public function report(): bool
    {
        do_log("massaging/providers".now()->toDateTimeString())->error("The provider {$this->provider} is not configured.");

        return false;
    }
}
