<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class UnknownMessageTypeException extends Exception implements ReportableException
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
        do_log("messaging/types")->error("Given type '{$this->type}' in provider '$this->provider' not found");

        return false;
    }
}
