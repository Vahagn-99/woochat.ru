<?php

namespace App\Console\Commands;

use App\Events\Messaging\MessageStatusReceived;
use App\Listeners\Massaging\SendMessageStatusAmo;
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
        $event = new MessageStatusReceived([
            "typeWebhook" => "outgoingMessageStatus",
            "chatId" => "37493972413@c.us",
            "instanceData" => [
                "idInstance" => 5700110738,
                "wid" => "37493270709@c.us",
                "typeInstance" => "whatsapp",
            ],
            "timestamp" => 1725825504,
            "idMessage" => "BAE5BD38923E6C94",
            "status" => "read",
            "sendByApi" => true,
        ], 'whatsapp');

        $listener = new SendMessageStatusAmo();

        $listener->handle($event);
    }
}
