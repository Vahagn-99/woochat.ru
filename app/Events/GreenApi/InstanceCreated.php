<?php

namespace App\Events\GreenApi;

use App\Models\Instance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstanceCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Instance $instance)
    {
    }

}
