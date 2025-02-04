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
        $instance = new self("Типь собшение '{$localType}' в провайдере '$fromProvider' не найдень", 404);
        $instance->type = $localType;
        $instance->provider = $fromProvider;

        return $instance;
    }

    public function report(): bool
    {
        do_log("messaging/types")->error("Тип '{$this->type}' в провайдере '$this->provider' не найдень");

        return false;
    }
}
