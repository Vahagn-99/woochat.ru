<?php

namespace Database\Seeders;

use App\Enums\InstanceStatus;
use App\Models\Instance;
use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'domain' => 'tech8.amocrm.ru',
            'password' => bcrypt('password'),
        ]);
//
//        Instance::factory()->create([
//            'id' => '1103961649',
//            'user_id' => $user->getKey(),
//            'name' => 'Test instance 1',
//            'token' => '51ac2d99bdac4774be095445340ff8881b3f3ff6ea5d492683',
//            'status' => InstanceStatus::NOT_AUTHORIZED,
//        ]);
    }
}
