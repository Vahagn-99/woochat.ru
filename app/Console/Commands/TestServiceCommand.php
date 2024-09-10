<?php

namespace App\Console\Commands;

use App\Events\Messaging\MessageReceived;
use App\Listeners\messaging\SendMessageAmo;
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
        $event = new MessageReceived([
            "typeWebhook" => "incomingMessageReceived",
            "instanceData" => [
                "idInstance" => 5700113017,
                "wid" => "79093119379@c.us",
                "typeInstance" => "whatsapp",
            ],
            "timestamp" => 1725967943,
            "idMessage" => "D5E2DE4FA2F1FC1C296A00FAD091661F",
            "senderData" => [
                "chatId" => "79172407972@c.us",
                "chatName" => "Алексей",
                "sender" => "79172407972@c.us",
                "senderName" => "Алексей",
                "senderContactName" => "Timly Class",
            ],
            "messageData" => [
                "typeMessage" => "textMessage",
                "textMessageData" => [
                    "textMessage" => "Ответ",
                ],
            ],
        ], 'whatsapp');

        $listener = new SendMessageAmo();

        $listener->handle($event);
    }
}
