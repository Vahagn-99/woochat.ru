<?php

namespace App\Events\Whatsapp;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInstanceOrdered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $name)
    {

    }
}
