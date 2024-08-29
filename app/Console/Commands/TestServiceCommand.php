<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'scheduler:works';

    protected $description = 'The command to test scheduler';

    public function handle(): void
    {
        do_log('scheduler')->info('test scheduler');
    }
}
