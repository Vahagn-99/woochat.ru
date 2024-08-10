<?php

namespace App\Listeners\AmoAccount;

use App\Events\AmoCRM\WidgetInstalled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAboutInstalling implements ShouldQueue
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
