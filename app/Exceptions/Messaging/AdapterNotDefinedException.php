<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;

class AdapterNotDefinedException extends ReportableException
{
    public function __construct(
        private readonly string $from,
        private readonly string $to,
        private readonly string $type
    ) {
        parent::__construct("There is no adapter '{$this->type}' in provider '{$this->from}' for '{$this->to}'");
    }

    public function report(): void
    {
        do_log("massaging/adapters".now()->toDateTimeString())->error("There is no adapter '{$this->type}' in provider '{$this->from}' for '{$this->to}'");
    }
}
