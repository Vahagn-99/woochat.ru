<?php

namespace Database\Factories;

use App\Enums\InstanceStatus;
use App\Models\WhatsappInstance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<WhatsappInstance>
 */
class InstanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'user_id' => User::factory()->create()->getKey(),
            'status' => Arr::random(InstanceStatus::cases()),
            'token' => $this->faker->uuid(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
