<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_contacts()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        Contact::factory()->count(3)->create(['patient_id' => $patient->id]);

        $response = $this->actingAs($user, 'api')->getJson('/api/contacts');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id', 'first_name', 'last_name', 'phone', 'email', 'patient_id', 'created_at', 'updated_at'
                         ]
                     ],
                     'links' => ['first', 'last', 'prev', 'next'],
                     'meta' => ['current_page', 'from', 'last_page', 'total']
                 ]);
    }

    public function test_store_contact()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $contactData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '123456789',
            'email' => 'john.doe@example.com',
            'patient_id' => $patient->id,
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => $contactData
                 ]);
    }

    public function test_show_contact()
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/contacts/' . $contact->id);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'id' => $contact->id,
                         'first_name' => $contact->first_name,
                         'last_name' => $contact->last_name,
                         'phone' => $contact->phone,
                         'email' => $contact->email,
                         'created_at' => $contact->created_at->toISOString(),
                         'updated_at' => $contact->updated_at->toISOString(),
                     ]
                 ]);
    }

    public function test_update_contact()
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create();
        $updatedData = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '987654321',
            'email' => 'jane.doe@example.com',
        ];

        $response = $this->actingAs($user, 'api')->putJson('/api/contacts/' . $contact->id, $updatedData);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => $updatedData
                 ]);
    }

    public function test_delete_contact()
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson('/api/contacts/' . $contact->id);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Contacto eliminado con éxito'
                 ]);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_get_contacts_by_patient()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $contact = Contact::factory()->create(['patient_id' => $patient->id]);

        $response = $this->actingAs($user, 'api')->getJson('/api/patients/' . $patient->id . '/contacts');

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         [
                             'id' => $contact->id,
                             'first_name' => $contact->first_name,
                             'last_name' => $contact->last_name,
                             'phone' => $contact->phone,
                             'email' => $contact->email,
                             'patient_id' => $contact->patient_id,
                         ]
                     ]
                 ]);
    }
}
