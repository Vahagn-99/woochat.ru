<?php

namespace App\Console\Commands;

use GreenApi\RestApi\GreenApiClient;
use Illuminate\Console\Command;

class TestWhatsAppSDKCommand extends Command
{
    const ID_INSTANCE = '1103961649';
    const API_TOKEN_INSTANCE = '51ac2d99bdac4774be095445340ff8881b3f3ff6ea5d492683';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $greenApi = new GreenApiClient(self::ID_INSTANCE, self::API_TOKEN_INSTANCE);
        $result = $greenApi->sending->sendMessage('37493270709@c.us', 'Message text');
        dd($result);
    }
}
