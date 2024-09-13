<?php

namespace App\Exceptions\Whatsapp;

use App\Exceptions\ReportableException;
use Exception;

class UnsupportedWebhookType extends Exception implements ReportableException
{
    public function report(): bool
    {
        do_log("whatsapp/webhooks")->warning($this->getMessage());

        return false;
    }
}
