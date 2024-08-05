<?php

namespace App\Console\Commands;

use App\Services\GreenApi\DTO\InstanceDTO;
use App\Services\GreenApi\Facades\GreenApi;
use App\Services\GreenApi\Messaging\Types\TextMessage;
use Illuminate\Console\Command;

class TestWhatsAppSDKCommand extends Command
{
    const ID_INSTANCE = '5700965447';
    const API_TOKEN_INSTANCE = 'f405f25b00144083b404e9de9e6d5ad480d66e6fe6e341d8ad';

    protected $signature = 'test:message';


    protected $description = 'The command to test whatsapp sdk';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $instance = new InstanceDTO(self::ID_INSTANCE, self::API_TOKEN_INSTANCE);
        $message = new TextMessage("37493270709@c.us", "test message");

        $messageId = GreenApi::fromDTO($instance)->massaging()->send($message);
    }
}
