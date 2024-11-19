<?php

namespace App\Console;

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class Scheduler extends ServiceProvider
{
    public function boot(): void
    {
        Schedule::command('whatsapp:sync-instances')->everyMinute();
        Schedule::command('whatsapp:block-unsubscribed-instances')->everyTwoHours();
        Schedule::command('whatsapp:logout-blocked-instances')->everyTwoHours();
        Schedule::command('whatsapp:delete-unused-instances')->dailyAt('19:55');
    }
}
