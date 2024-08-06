<?php

namespace App\Listeners\AmoCrm;

use App\Events\AmoCrm\WidgetInstalled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleLicense
{
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
