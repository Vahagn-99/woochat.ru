<?php

namespace App\Exceptions\AmoChat;

use App\Exceptions\ReportableException;

class AmoChatRequestException extends ReportableException
{
    public function report(): bool
    {
        do_log('amochat/request')->error($this->getMessage());

        return false;
    }
}
