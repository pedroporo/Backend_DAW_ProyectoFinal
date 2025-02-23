<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para verificar que un contacto se puede crear correctamente.
     *
     * @return void
     */
    public function test_create_contact()
    {
        $patient = Patient::factory()->create();

        // Datos del contacto
        $data = [
            'first_name' => 'Juan',
            'last_name' => 'Guillermo',
            'phone' => '1234567890',
            'patient_id' => $patient->id,
            'relationship' => 'Amigo',
        ];

        $contact = Contact::create($data);

        $this->assertDatabaseHas('contacts', $data);
    }

    /**
     * Test para verificar que el modelo Contact tiene la relación correcta con Patient.
     *
     * @return void
     */
    public function test_contact_belongs_to_patient()
    {
        $patient = Patient::factory()->create();
        $contact = Contact::factory()->create([
            'patient_id' => $patient->id
        ]);

        $this->assertEquals($contact->patient->id, $patient->id);
    }

    /**
     * Test para verificar que los atributos ocultos están correctamente configurados.
     *
     * @return void
     */
    public function test_contact_hidden_attributes()
    {
        $contact = Contact::factory()->create();

        $this->assertArrayNotHasKey('created_at', $contact->toArray());
        $this->assertArrayNotHasKey('updated_at', $contact->toArray());
    }

    /**
     * Test para verificar la validación de los campos al crear un contacto.
     *
     * @return void
     */
    public function test_contact_validation()
    {
        $response = $this->postJson('/api/contacts', [
            'last_name' => 'John Dolmayan',
            'phone' => '1234567890',
            'patient_id' => 1,
            'relationship' => 'Amigo',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['first_name']);
    }

    /**
     * Test para verificar la actualización de un contacto.
     *
     * @return void
     */
    public function test_update_contact()
    {
        $patient = Patient::factory()->create();
        $contact = Contact::factory()->create([
            'patient_id' => $patient->id
        ]);
        $updatedData = [
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'phone' => '0987654321',
            'patient_id' => $patient->id,
            'relationship' => 'Hermano',
        ];
        $contact->update($updatedData);
        $this->assertDatabaseHas('contacts', $updatedData);
    }

    /**
     * Test para verificar la eliminación de un contacto.
     *
     * @return void
     */
    public function test_delete_contact()
    {
        $patient = Patient::factory()->create();
        $contact = Contact::factory()->create([
            'patient_id' => $patient->id
        ]);
        $contact->delete();
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
