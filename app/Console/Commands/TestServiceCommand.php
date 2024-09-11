<?php

namespace App\Console\Commands;

use App\Services\AmoChat\Facades\AmoChat;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test any service';

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $channel = AmoChat::connector()->connect("e505c508-64a4-4070-bca9-e99d10f9c3fe");
    }
}
