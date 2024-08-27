<?php

namespace App\Console\Commands;

use App\Base\Messaging\Factory;
use App\Services\Whatsapp\DTO\InstanceDTO;
use App\Services\Whatsapp\Facades\Whatsapp;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test services';

    /**
     * @throws \App\Exceptions\Messaging\ProviderNotConfiguredException
     * @throws \App\Exceptions\Messaging\AdapterNotDefinedException
     * @throws \App\Exceptions\Messaging\UnknownMessageTypeException
     */
    public function handle(): void
    {
        $imageMessage = [
            "account_id" => "f3d8b848-e6bd-4f4d-afc7-fb31df767858",
            "time" => 1724748977,
            "message" => [
                "receiver" => [
                    "id" => "679ffc8c-3096-4f81-b370-49b5b13e763d",
                    "name" => "Vahagn Ghukasyan",
                    "phone" => "37493270709",
                    "client_id" => "37493270709@c.us",
                ],
                "sender" => [
                    "id" => "c6ebb203-998f-469f-80f7-140cd33ef0b5",
                    "name" => "Вааган Dev",
                ],
                "source" => [
                    "external_id" => "9cdd4cea-6596-432c-b780-7daa44b7c524",
                ],
                "conversation" => [
                    "id" => "6c9fbeec-f8d3-4b15-bae0-f2bb301049de",
                    "client_id" => "37493270709@c.us",
                ],
                "timestamp" => 1724748977,
                "msec_timestamp" => 1724748977808,
                "message" => [
                    "id" => "53dec98e-6bec-4772-b336-2fe802f6ddd5",
                    "type" => "picture",
                    "text" => "test",
                    "markup" => null,
                    "tag" => null,
                    "media" => "https://drive-b.amocrm.ru/download/399f9424-260f-59d6-9083-6f00f12c6e0b/1f654c95-0388-4349-a9e3-b6fc81f00f0e/1536a6dd-2bde-437b-b2ed-ea45d6768a35/Screenshot-from-2024-07-11-12-10-21.png",
                    "thumbnail" => "https://drive-b.amocrm.ru/download/399f9424-260f-59d6-9083-6f00f12c6e0b/1f654c95-0388-4349-a9e3-b6fc81f00f0e/1536a6dd-2bde-437b-b2ed-ea45d6768a35/68086edd-2ccd-4e2a-937f-35dcf2775aeb/Screenshot-from-2024-07-11-12-10-21_320_141.png",
                    "file_name" => "Screenshot from 2024-07-11 12-10-21.png",
                    "file_size" => 61055,
                ],
            ],
            "scope_id" => "eb2f1b4f-c1bd-4d47-9158-01842999cf65_f3d8b848-e6bd-4f4d-afc7-fb31df767858",
        ];

        $factory = Factory::make();
        $factory->from('amochat', $imageMessage['message']['message']['type']);
        $model = $factory->to('whatsapp', $imageMessage['message']);
        $instance = InstanceDTO::fromArray([
            'id' => '5700107428',
            'token' => '43792e7a84714397b72abb7af714dd438518e9bdcd504959bd',
        ]);

        $resp = Whatsapp::for($instance)->massaging()->send($model);

        dd($resp);
    }
}
