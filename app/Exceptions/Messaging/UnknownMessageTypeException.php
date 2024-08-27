<?php

namespace App\Exceptions\Messaging;

use App\Exceptions\ReportableException;

class UnknownMessageTypeException extends ReportableException
{
    public function __construct(private readonly string $type, private readonly string $provider = 'amocrm')
    {
        parent::__construct();
    }

    public function report(): void
    {
        do_log("{$this->provider}/message-type-".now()->toDateTimeString())->error('Unknown message type: '.$this->type);
    }
}
