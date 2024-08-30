<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test any service';

    public function handle(): void
    {

        $payload = [
            "account_id" => "f3d8b848-e6bd-4f4d-afc7-fb31df767858",
            "time" => 1725016502,
            "message" => [
                "receiver" => [
                    "id" => "b1e270d7-0100-4ae7-9dab-7d72aba03891",
                    "name" => "Timly Class",
                    "phone" => "79655841483"
                ],
                "sender" => [
                    "id" => "c20fc2aa-e6a8-49c6-857c-f6acd280d3ce",
                    "name" => "Алексей"
                ],
                "source" => [
                    "external_id" => "9ce3ab3e-fe80-4a68-be41-a6a2c7dababd"
                ],
                "conversation" => [
                    "id" => "01bb61bd-dd89-4253-82f1-88f5912e5084"
                ],
                "timestamp" => 1725016502,
                "msec_timestamp" => 1725016502347,
                "message" => [
                    "id" => "b1c042a9-5c43-4a41-9087-b53150419dda",
                    "type" => "text",
                    "text" => "ghbdtn",
                    "markup" => null,
                    "tag" => null,
                    "media" => null,
                    "thumbnail" => null,
                    "file_name" => null,
                    "file_size" => 0
                ]
            ],
            "scope_id" => "eb2f1b4f-c1bd-4d47-9158-01842999cf65_f3d8b848-e6bd-4f4d-afc7-fb31df767858"
        ];

        dd($payload['message']['receiver']['phone'].'@c.us');
    }
}
