<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Equip;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'),
            'role' => 'administrador',
            'phone' => \Faker\Provider\es_ES\PhoneNumber::mobileNumber(),
        ]);

        for ($i = 1; $i <= 30; $i++) {
            $name = fake()->name();
            User::create([
                'name' => $name,
                'email' => str_replace([' ', ',', '.'], '', $name) . '@operador.com',
                'password' => Hash::make('1234'),
                'role' => 'operador',
                'phone' => \Faker\Provider\es_ES\PhoneNumber::mobileNumber(),
            ]);
        }
    }
}
