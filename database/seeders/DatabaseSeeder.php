<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'domain' => 'tech8.amocrm.ru',
            'password' => bcrypt('password'),
        ]);
    }
}
