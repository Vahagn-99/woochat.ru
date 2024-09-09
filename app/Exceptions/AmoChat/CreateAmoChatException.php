<?php

namespace App\Exceptions\AmoChat;

use App\Exceptions\ReportableException;
use Exception;

class CreateAmoChatException extends Exception implements ReportableException
{
    public function report(): bool
    {
        do_log('amochat/create-chat')->error($this->getMessage());

        return true;
    }
}
