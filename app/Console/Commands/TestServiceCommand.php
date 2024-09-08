<?php

namespace App\Console\Commands;

use App\Events\Messaging\MessageReceived;
use App\Listeners\Massaging\SendMessageWhatsapp;
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
            "account_id" => "3dffd2b6-ad0d-4ee9-a158-d8e53c76c33f",
            "time" => 1725825180,
            "message" => [
                "receiver" => [
                    "id" => "1e78addd-d2c0-4fd6-a95d-93555f9318a1",
                    "name" => "Lusine Hakobyan",
                    "phone" => "37493972413",
                    "client_id" => "37493972413@c.us",
                ],
                "sender" => [
                    "id" => "c6ebb203-998f-469f-80f7-140cd33ef0b5",
                    "name" => "Вааган Dev",
                ],
                "source" => [
                    "external_id" => "23422931",
                ],
                "conversation" => [
                    "id" => "c1034ea3-584b-4d21-8cf1-abf54fb14823",
                    "client_id" => "37493972413@c.us",
                ],
                "timestamp" => 1725825180,
                "msec_timestamp" => 1725825180497,
                "message" => [
                    "id" => "c1f1a433-1af7-4015-9761-3a39ddafa872",
                    "type" => "text",
                    "text" => "Hy",
                    "markup" => null,
                    "tag" => null,
                    "media" => null,
                    "thumbnail" => null,
                    "file_name" => null,
                    "file_size" => 0,
                ],
            ],
            "scope_id" => "eb2f1b4f-c1bd-4d47-9158-01842999cf65_3dffd2b6-ad0d-4ee9-a158-d8e53c76c33f",
        ], 'amochat');

        $listener = new SendMessageWhatsapp();

        $listener->handle($event);
    }
}
