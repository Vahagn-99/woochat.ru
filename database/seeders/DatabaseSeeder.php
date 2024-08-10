<?php

namespace Database\Seeders;

use App\Enums\InstanceStatus;
use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate([
            'domain' => 'tech8.amocrm.ru',
            'password' => bcrypt('password'),
        ]);

        $user->whatsappInstances()->create([
            'id' => '5700100379',
            'name' => 'Test message instance',
            'status' => InstanceStatus::AUTHORIZED,
            'token' => 'b4b21d332a94484ea3592a169c5ee70f374ddc532ea04cb9b0',
            'phone' => '37493270709@c.us'
        ]);
    }
}
