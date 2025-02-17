<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Patient;
use App\Models\IncomingCall;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncomingCall>
 */
class IncomingCallFactory extends Factory
{
    protected $model = IncomingCall::class;
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
            'type' => $this->faker->randomElement([
                'social_emergency', 'medical_emergency', 'loneliness_crisis', 
                'unanswered_alarm', 'absence_notification', 'data_update', 
                'accidental', 'info_request', 'complaint', 'social_call', 
                'medical_appointment', 'other'
            ]),
        ];
    }
}
