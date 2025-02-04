<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;
use Exception;

class AdapterNotDefinedException extends Exception implements ReportableException
{
    public function __construct(
        private readonly string $from,
        private readonly string $to,
        private readonly string $type
    ) {
        parent::__construct("Адаптер сообщения '{$this->type}' в провайдере '{$this->from}' для '{$this->to}' не найден.");
    }

    public function report(): bool
    {
        do_log("messaging/adapters")->error("Не найдень '{$this->type}' в провайдере '{$this->from}' для '{$this->to}'");

        return false;
    }
}
