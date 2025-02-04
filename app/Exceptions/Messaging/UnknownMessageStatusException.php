<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class UnknownMessageStatusException extends Exception implements ReportableException
{
    private string $status;

    private string $provider;

    public static function status(string $status, string $provider): UnknownMessageStatusException
    {
        $instance = new self("Статус собшение '{$status}' для провайдера '$provider' не найдено", 404);
        $instance->status = $status;
        $instance->provider = $provider;

        return $instance;
    }

    public function report(): bool
    {
        do_log("messaging/delivery-statuses")->error("Статус собшение '{$this->status}' для провайдера '$this->provider' не найдено");

        return false;
    }
}
