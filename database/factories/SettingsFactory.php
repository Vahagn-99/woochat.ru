<?php

namespace Database\Factories;

use App\Models\Instance;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Settings>
 */
class SettingsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'instance_id' => Instance::factory()->create()->getKey(),
            'pipeline_id' => $this->faker->numberBetween(84545, 5645454),
            'status_id' => $this->faker->numberBetween(84545, 5645454)
        ];
    }
}
