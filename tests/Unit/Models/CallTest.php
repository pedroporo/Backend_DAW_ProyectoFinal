<?php

namespace Tests\Unit;

use App\Models\Call;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CallTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_patient()
    {
        $patient = Patient::factory()->create();
        $call = Call::factory()->create(['patient_id' => $patient->id]);

        $this->assertInstanceOf(Patient::class, $call->patient);
        $this->assertEquals($patient->id, $call->patient->id);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $call = Call::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $call->user);
        $this->assertEquals($user->id, $call->user->id);
    }

    /** @test */
    public function it_has_a_formatted_timestamp()
    {
        $call = Call::factory()->create(['timestamp' => now()]);

        $this->assertEquals(now()->format('d-m-Y H:i'), $call->formatted_timestamp);
    }

    /** @test */
    public function it_can_create_a_call()
    {
        $patient = Patient::factory()->create();
        $user = User::factory()->create();

        $call = Call::create([
            'timestamp' => now(),
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'description' => 'Test call description',
        ]);

        $this->assertDatabaseHas('calls', [
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'description' => 'Test call description',
        ]);
    }
}
