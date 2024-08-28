<?php

namespace App\Exceptions\AmoChat;

use App\Exceptions\ReportableException;
use Exception;

class AmoChatRequestException extends Exception implements ReportableException
{
    public function report(): bool
    {
        do_log('amochat/request')->error($this->getMessage());

        return true;
    }
}
