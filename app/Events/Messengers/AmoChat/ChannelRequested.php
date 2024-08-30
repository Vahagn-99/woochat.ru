<?php

namespace App\Events\Messengers\AmoChat;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChannelRequested
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user)
    {
        //
    }
}
