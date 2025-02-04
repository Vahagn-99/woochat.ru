<?php

namespace App\Events\Subscription;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class Trial
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public Carbon $expired_at)
    {
    }
}
