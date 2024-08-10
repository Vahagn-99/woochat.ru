<?php

namespace App\Events\Whatsapp;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $webhookPayload)
    {
    }
}
