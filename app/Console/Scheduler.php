<?php

namespace App\Console;

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class Scheduler extends ServiceProvider
{
    public function boot(): void
    {
        Schedule::command('sync:instances')->everyMinute()->withoutOverlapping();
        Schedule::command('delete:instances')->dailyAt('19:55');
        Schedule::command('subscription:daily_check_renewal')->dailyAt('17:55');
        Schedule::command('instance:daily_check_blocked')->dailyAt('14:55');
    }
}
