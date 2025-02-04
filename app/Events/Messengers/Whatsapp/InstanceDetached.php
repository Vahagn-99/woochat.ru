<?php

namespace App\Events\Messengers\Whatsapp;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceDetached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Settings $settings, public User $user)
    {

    }
}
