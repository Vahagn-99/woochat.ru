<?php

namespace App\Console\Commands;

use App\Services\GreenApi\DTO\InstanceDTO;
use App\Services\GreenApi\Facades\GreenApi;
use App\Services\GreenApi\Messaging\MessagingServiceInterface;
use App\Services\GreenApi\Messaging\Send\SendMessageServiceInterface;
use App\Services\GreenApi\Messaging\Types\TextMessage;
use GreenApi\RestApi\GreenApiClient;
use Illuminate\Console\Command;

class TestWhatsAppSDKCommand extends Command
{
    const ID_INSTANCE = '1103961649';
    const API_TOKEN_INSTANCE = '51ac2d99bdac4774be095445340ff8881b3f3ff6ea5d492683';

    protected $signature = 'test:message';


    protected $description = 'The command to test whatsapp sdk';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        GreenApi::fromDTO(new InstanceDTO(self::ID_INSTANCE, self::API_TOKEN_INSTANCE));
        $messageId = GreenApi::massaging()->send(new TextMessage("37493270709", "test message"));
        dd($messageId);
    }
}
