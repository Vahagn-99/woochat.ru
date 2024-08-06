<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amo_message_id' => $this->faker->uuid(),
            'whatsapp_message_id' => $this->faker->uuid(),
            'chat_id' => $this->faker->uuid(),
        ];
    }
}
