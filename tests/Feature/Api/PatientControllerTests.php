<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_patients()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Patient::factory(3)->create();

        $response = $this->getJson('/api/patients');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'name', 'zone_id', 'created_at', 'updated_at'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_can_create_a_patient()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $zone = Zone::factory()->create();

        $patientData = [
            'name' => 'Test Patient',
            'zone_id' => $zone->id,
            'description' => 'Patient description',
        ];

        $response = $this->postJson('/api/patients', $patientData);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => 'Test Patient',
                'zone_id' => $zone->id,
            ]
        ]);
    }

    /** @test */
    public function it_can_get_a_patient_by_id()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $patient = Patient::factory()->create();

        $response = $this->getJson("/api/patients/{$patient->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $patient->id,
                'name' => $patient->name,
            ]
        ]);
    }

    /** @test */
    public function it_can_update_a_patient()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $patient = Patient::factory()->create();
        $zone = Zone::factory()->create();

        $updatedData = [
            'name' => 'Updated Name',
            'zone_id' => $zone->id,
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/api/patients/{$patient->id}", $updatedData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => 'Updated Name',
                'zone_id' => $zone->id,
            ]
        ]);
    }

    /** @test */
    public function it_can_delete_a_patient()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $patient = Patient::factory()->create();

        $response = $this->deleteJson("/api/patients/{$patient->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
    }

    /** @test */
    public function it_can_get_patients_by_zone()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $zone = Zone::factory()->create();
        $patients = Patient::factory(3)->create(['zone_id' => $zone->id]);

        $response = $this->getJson("/api/zones/{$zone->id}/patients");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'name', 'zone_id', 'created_at', 'updated_at'
                ]
            ]
        ]);
    }
}
