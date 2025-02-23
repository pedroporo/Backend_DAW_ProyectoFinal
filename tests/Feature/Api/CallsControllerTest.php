<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Patient;
use App\Models\IncomingCall;
use App\Models\OutgoingCall;
use Tests\TestCase;

class CallControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_calls_for_a_patient()
    {
        $patient = Patient::factory()->create();
        $incomingCall = IncomingCall::factory()->create(['patient_id' => $patient->id]);
        $outgoingCall = OutgoingCall::factory()->create(['patient_id' => $patient->id]);

        $response = $this->getJson("/api/patients/{$patient->id}/calls");

        $response->assertStatus(200)
                 ->assertJson([
                     'incoming_calls' => [
                         ['id' => $incomingCall->id, 'patient_id' => $patient->id, 'timestamp' => $incomingCall->timestamp->toISOString()],
                     ],
                     'outgoing_calls' => [
                         ['id' => $outgoingCall->id, 'patient_id' => $patient->id, 'timestamp' => $outgoingCall->timestamp->toISOString()],
                     ],
                 ]);
    }

    /** @test */
    public function it_returns_404_if_patient_not_found()
    {
        $response = $this->getJson('/api/patients/999999/calls');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Patient not found',
                 ]);
    }

    /** @test */
    public function it_can_filter_calls_by_date_and_type()
    {
        $patient = Patient::factory()->create();
        $incomingCall = IncomingCall::factory()->create([
            'patient_id' => $patient->id,
            'timestamp' => now()->subDays(1),
        ]);
        $outgoingCall = OutgoingCall::factory()->create([
            'patient_id' => $patient->id,
            'timestamp' => now(),
        ]);

        $response = $this->getJson('/api/filter-calls?type=incoming&date=' . now()->subDays(1)->toDateString());

        $response->assertStatus(200)
                 ->assertJson([
                     'incoming_calls' => [
                         ['id' => $incomingCall->id, 'patient_id' => $patient->id, 'timestamp' => $incomingCall->timestamp->toISOString()],
                     ],
                 ])
                 ->assertJsonMissing([
                     'outgoing_calls' => [
                         ['id' => $outgoingCall->id],
                     ],
                 ]);
    }

    /** @test */
    public function it_can_filter_calls_by_zone()
    {
        $zone1Patient = Patient::factory()->create(['zone_id' => 1]);
        $zone2Patient = Patient::factory()->create(['zone_id' => 2]);

        $zone1IncomingCall = IncomingCall::factory()->create(['patient_id' => $zone1Patient->id]);
        $zone2OutgoingCall = OutgoingCall::factory()->create(['patient_id' => $zone2Patient->id]);

        $response = $this->getJson('/api/filter-calls?zone=1');

        $response->assertStatus(200)
                 ->assertJson([
                     'incoming_calls' => [
                         ['id' => $zone1IncomingCall->id],
                     ],
                 ])
                 ->assertJsonMissing([
                     'outgoing_calls' => [
                         ['id' => $zone2OutgoingCall->id],
                     ],
                 ]);
    }
}
