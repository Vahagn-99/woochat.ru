<?php

namespace App\Console\Commands;

use App\Events\Messaging\MessageReceived;
use App\Listeners\Massaging\SendMessageAmo;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test any service';

    public function handle(): void
    {
        $event = new MessageReceived([
            "typeWebhook" => "incomingMessageReceived",
            "instanceData" => [
                "idInstance" => 5700110738,
                "wid" => "37493270709@c.us",
                "typeInstance" => "whatsapp",
            ],
            "timestamp" => 1725821326,
            "idMessage" => "3AB0AC8554CD7AD8F592",
            "senderData" => [
                "chatId" => "37493972413@c.us",
                "chatName" => "Lusine Hakobyan",
                "sender" => "37493972413@c.us",
                "senderName" => "Lusine Hakobyan",
                "senderContactName" => "Մամաս",
            ],
            "messageData" => [
                "typeMessage" => "textMessage",
                "textMessageData" => [
                    "textMessage" => "Hdbdbdjdjd",
                ],
            ],
        ], 'whatsapp');

        $listener = new SendMessageAmo();

        $listener->handle($event);
    }
}
