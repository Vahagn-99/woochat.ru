<?php

namespace App\Events\Messengers\Whatsapp;

use App\Models\User;
use App\Models\WhatsappInstance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceDetached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public WhatsappInstance $instance, public User $user)
    {

    }
}
