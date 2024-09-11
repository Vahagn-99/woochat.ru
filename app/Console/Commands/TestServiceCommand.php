<?php

namespace App\Console\Commands;

use App\Events\Messengers\AmoChat\ChannelRequested;
use App\Listeners\Messengers\AmoChat\ConnectChannel;
use App\Models\User;
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
        $listener = new ConnectChannel();
        $listener->handle(new ChannelRequested(User::first()));
    }
}
