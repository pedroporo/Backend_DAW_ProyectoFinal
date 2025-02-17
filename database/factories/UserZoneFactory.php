<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserZoneFactory extends Factory
{
    // Definir el modelo que utiliza este factory
    protected $model = \App\Models\UserZone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id, 
            'zone_id' => Zone::inRandomOrder()->first()->id,  
        ];
    }
}