<?php

namespace App\Listeners\Widget;

use App\Events\Widget\WidgetInstalled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleLicense implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WidgetInstalled $event): void
    {
        //
    }
}
