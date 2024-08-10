<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\WhatsappInstance;
use App\Models\Message;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $instances = WhatsappInstance::factory()->count(3)->create(['user_id' => $user->getKey()]);
            foreach ($instances as $instance) {
                Settings::factory()->create(['instance_id' => $instance->getKey()]);
                $chats = Chat::factory()->count(3)->create(['instance_id' => $instance->getKey()]);
                foreach ($chats as $chat) {
                    Message::factory()->count(15)->create(['chat_id' => $chat->getKey()]);
                }
            }
        }
    }
}
