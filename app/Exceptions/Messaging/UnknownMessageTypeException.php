<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;

class UnknownMessageTypeException extends ReportableException
{
    public function __construct(private readonly string $type, private readonly string $provider = 'amochat')
    {
        parent::__construct('Unknown message type: '.$this->type);
    }

    public function report(): void
    {
        do_log("{$this->provider}/types-".now()->toDateTimeString())->error('Unknown message type: '.$this->type);
    }
}
