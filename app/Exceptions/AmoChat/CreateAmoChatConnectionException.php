<?php

namespace App\Exceptions\AmoChat;

use App\Exceptions\ReportableException;
use Exception;

class CreateAmoChatConnectionException extends Exception implements ReportableException
{
    public function report(): bool
    {
        do_log('amochat/create-connection')->error($this->getMessage());

        return true;
    }
}
