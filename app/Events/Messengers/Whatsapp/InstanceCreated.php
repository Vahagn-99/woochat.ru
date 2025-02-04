<?php

namespace App\Events\Messengers\Whatsapp;

use App\Models\WhatsappInstance;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public WhatsappInstance $instance)
    {
    }
}
