<?php

namespace App\Console;

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class Scheduler extends ServiceProvider
{
    public function boot(): void
    {
        Schedule::command('whatsapp:sync-instances')->everyMinute()->withoutOverlapping();
        Schedule::command('whatsapp:delete-unused-instances')->dailyAt('19:55');
        Schedule::command('whatsapp:renew-blocked-instances')->everyTwoHours()->withoutOverlapping();

        /** Обработка подписок */
        Schedule::command('subscription:daily-check-renewal')->everyTwoHours()->withoutOverlapping();
    }
}
