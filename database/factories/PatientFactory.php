<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\es_ES\Person;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'birth_date' => $this->faker->date('d-m-Y'),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->numberBetween(00000, 99999),
            'dni' => \Faker\Provider\es_ES\Person::dni(),
            'health_card_number' => $this->faker->numberBetween(00000000, 99999999),
            'phone' => \Faker\Provider\es_ES\PhoneNumber::mobileNumber(),
            'email' => $this->faker->email,
            'zone_id' => $this->faker->randomNumber(),
            'user_id'=> $this->faker->randomNumber(),
            'personal_situation' => $this->faker->sentence(),
            'health_situation' => $this->faker->sentence(),
            'housing_situation' => $this->faker->sentence(),
            'personal_autonomy' => $this->faker->sentence(),
            'economic_situation' => $this->faker->sentence(),
        ];
    }
}
