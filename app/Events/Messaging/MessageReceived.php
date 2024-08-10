<?php

namespace App\Events\Messaging;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload, public string $from)
    {
    }
}
