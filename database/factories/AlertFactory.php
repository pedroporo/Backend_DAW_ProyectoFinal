<?php

namespace Database\Factories;

use App\Enums\Alarms_type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $day = $this->faker->numberBetween(0, 7);
        return [
            'zone_id' => $this->faker->numberBetween(1, 30),
            'type' => $this->faker->randomElement(Alarms_type::cases())->value,
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'weekday' => $day == 0 ? null : $day,
            'description' => $this->faker->sentence(),
        ];
    }
}
