<?php

namespace App\Events\Whatsapp;

use App\Models\WhatsappInstance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceSettingsSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public WhatsappInstance $instance)
    {

    }
}
