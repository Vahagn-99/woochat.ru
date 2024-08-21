<?php

namespace App\Console\Commands;

use App\Services\AmoChat\Facades\AmoChat;
use App\Services\AmoChat\Messaging\Actor\Actor;
use App\Services\AmoChat\Messaging\Actor\Profile;
use App\Services\AmoChat\Messaging\Types\AmoMessage;
use App\Services\AmoChat\Messaging\Types\Text;
use Illuminate\Console\Command;

class TestServiceCommand extends Command
{
    protected $signature = 'test:service';

    protected $description = 'The command to test services';

    public function handle(): void
    {
        //scope  eb2f1b4f-c1bd-4d47-9158-01842999cf65_3dffd2b6-ad0d-4ee9-a158-d8e53c76c33f
        $sender = new Actor("test-id", 'Vahagn', new Profile("37493270709"));
        // App\Services\AmoChat\Chat\Create\AmoChat^ {#819
        //  +id: "49f5366b-1950-496b-a4f3-e60817f5ee95"
        //  +user_id: "fca19227-935c-428c-9153-4b766bf80569"
        //  +user_name: "Vahagn"
        //  +user_client_id: "test-id"
        //  +user_avatar: null
        //  +user_profile_phone: "37493270709"
        //  +user_profile_email: null
        //}
        $payload = new Text("49f5366b-1950-496b-a4f3-e60817f5ee95", "test message");
        $message = new AmoMessage(sender: $sender, payload: $payload, conversation_id: "49f5366b-1950-496b-a4f3-e60817f5ee95", msgid: "test-id-".uniqid());
        $sent = AmoChat::messaging("eb2f1b4f-c1bd-4d47-9158-01842999cf65_3dffd2b6-ad0d-4ee9-a158-d8e53c76c33f")->send($message);

        dd($sent);
    }
}
