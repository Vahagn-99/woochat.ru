<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;

class UnknownMessageTypeException extends ReportableException
{
    private string $type;

    private string $provider;

    public static function localType(string $localType, string $fromProvider): UnknownMessageTypeException
    {
        $instance = new self("local type '{$localType}' in provider '$fromProvider' not found", 404);
        $instance->type = $localType;
        $instance->provider = $fromProvider;

        return $instance;
    }

    public function report(): bool
    {
        do_log("messaging/types-".now()->toDateString())->error("local type '{$this->type}' in provider '$this->provider' not found");

        return false;
    }
}
