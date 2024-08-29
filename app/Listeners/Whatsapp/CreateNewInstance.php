<?php

namespace App\Listeners\Whatsapp;

use App\Events\Whatsapp\NewInstanceOrdered;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateNewInstance implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(NewInstanceOrdered $event): void
    {
        Whatsapp::instance()->create($event->name);
    }
}
