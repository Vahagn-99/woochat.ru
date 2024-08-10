<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\WhatsappInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chat>
 */
class ChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amo_chat_id' => $this->faker->uuid(),
            'whatsapp_chat_id' => $this->faker->uuid(),
            'instance_id' => WhatsappInstance::factory()->create()->getKey(),
        ];
    }
}
