<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OutgoingCall;
use App\Models\Alert;
use App\Models\Patient;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OutgoingCall>
 */
class OutgoingCallFactory extends Factory
{
    protected $model = OutgoingCall::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'timestamp' => $this->faker->dateTime,
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'description' => $this->faker->text,
            'is_planned' => $this->faker->boolean,
            'alarm_id' => Alert::factory(),
    

        ];
    }
}
