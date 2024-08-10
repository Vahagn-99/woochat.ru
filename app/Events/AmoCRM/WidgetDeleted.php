<?php

namespace App\Events\AmoCRM;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WidgetDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user)
    {
        //
    }
}
