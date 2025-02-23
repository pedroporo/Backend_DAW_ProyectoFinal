<?php

namespace Tests\Unit;

use App\Models\Patient;
use App\Models\Zone;
use App\Models\Contact;
use App\Models\Call;
use App\Models\Alert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_zone()
    {
        $zone = Zone::factory()->create();
        $patient = Patient::factory()->create(['zone_id' => $zone->id]);

        $this->assertInstanceOf(Zone::class, $patient->zone);
        $this->assertEquals($zone->id, $patient->zone->id);
    }

    /** @test */
    public function it_can_have_contacts()
    {
        $patient = Patient::factory()->create();
        $contact = Contact::factory()->create([
            'patient_id' => $patient->id,
            'name' => 'Contact Name',
            'phone' => '123456789',
            'email' => 'contact@example.com',
        ]);

        $this->assertCount(1, $patient->contacts);
        $this->assertEquals('Contact Name', $contact->name);
        $this->assertEquals('123456789', $contact->phone);
        $this->assertEquals('contact@example.com', $contact->email);
    }

    /** @test */
    public function it_can_have_calls()
    {
        $patient = Patient::factory()->create();
        $call = Call::factory()->create([
            'patient_id' => $patient->id,
            'timestamp' => now(),
            'user_id' => 1,
            'description' => 'Test call description',
        ]);

        $this->assertCount(1, $patient->calls);
        $this->assertEquals('Test call description', $call->description);
    }

    /** @test */
    public function it_can_have_alerts()
    {
        $patient = Patient::factory()->create();
        $alert = Alert::factory()->create([
            'patient_id' => $patient->id,
            'message' => 'Test alert message',
            'status' => 'pending',
        ]);

        $this->assertCount(1, $patient->alerts);
        $this->assertEquals('Test alert message', $alert->message);
        $this->assertEquals('pending', $alert->status);
    }

    /** @test */
    public function it_has_hidden_timestamps()
    {
        $patient = Patient::factory()->create();

        $this->assertArrayNotHasKey('created_at', $patient->getAttributes());
        $this->assertArrayNotHasKey('updated_at', $patient->getAttributes());
    }

    /** @test */
    public function it_can_be_created_with_required_fields()
    {
        $zone = Zone::factory()->create();

        $patientData = [
            'name' => 'John',
            'last_name' => 'Guillerme',
            'birth_date' => '2002-05-15',
            'address' => 'Street 123',
            'city' => 'City Name',
            'postal_code' => '12345',
            'dni' => '12345678A',
            'health_card_number' => '987654321',
            'phone' => '555123456',
            'email' => 'john@example.com',
            'zone_id' => $zone->id,
            'personal_situation' => 'Single',
            'health_situation' => 'Good',
            'housing_situation' => 'Renting',
            'personal_autonomy' => 'Independent',
            'economic_situation' => 'Stable',
        ];

        $patient = Patient::create($patientData);

        $this->assertDatabaseHas('patients', [
            'name' => 'John',
            'last_name' => 'Guillerme',
            'dni' => '12345678A',
            'zone_id' => $zone->id,
        ]);
    }

    /** @test */
    public function it_can_update_patient_information()
    {
        $patient = Patient::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'city' => 'Updated City',
        ];

        $patient->update($updatedData);

        $this->assertDatabaseHas('patients', [
            'name' => 'Updated Name',
            'city' => 'Updated City',
        ]);
    }

    /** @test */
    public function it_can_delete_a_patient()
    {
        $patient = Patient::factory()->create();

        $patient->delete();

        $this->assertModelMissing($patient);
    }
}
