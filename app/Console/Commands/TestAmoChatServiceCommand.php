<?php

namespace App\Console\Commands;

use App\Models\AmoConnection;
use App\Models\Chat;
use App\Models\Instance;
use App\Models\User;
use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\Types\Actor;
use App\Services\AmoChat\Messaging\Types\Message;
use App\Services\AmoChat\Messaging\Types\Text;
use Exception;
use Illuminate\Console\Command;

class TestAmoChatServiceCommand extends Command
{
    protected $signature = 'test:message';


    protected $description = 'The command to test services';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
    }
}
