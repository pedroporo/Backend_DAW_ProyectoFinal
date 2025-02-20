<?php

namespace Database\Seeders;

use App\Models\IncomingCall;
use Illuminate\Database\Seeder;
use App\Models\OutgoingCall;

class CallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             IncomingCall::factory()->count(10)->create();
             OutgoingCall::factory()->count(10)->create();
    }
}
