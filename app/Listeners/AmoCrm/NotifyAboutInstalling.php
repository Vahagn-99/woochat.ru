<?php

namespace App\Listeners\AmoCrm;

use App\Events\AmoCrm\WidgetInstalled;

class NotifyAboutInstalling
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
