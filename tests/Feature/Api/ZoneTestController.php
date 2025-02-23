<?php

namespace Tests\Feature\Api;

use App\Models\Zone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoneTestController extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para listar todas las zonas (index).
     *
     * @return void
     */
    public function test_index()
    {
        Zone::factory()->count(20)->create();

        $response = $this->getJson('/api/zones');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [],
                     'links' => [],
                     'meta' => []
                 ]);
    }

    /**
     * Test para crear una nueva zona (store).
     *
     * @return void
     */
    public function test_store()
    {
        $data = [
            'name' => 'New Zone',
            'description' => 'A new zone for testing'
        ];

        $response = $this->postJson('/api/zones', $data);
        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'name' => 'New Zone',
                         'description' => 'A new zone for testing'
                     ]
                 ]);

        $this->assertDatabaseHas('zones', $data);
    }

    /**
     * Test para mostrar una zona específica (show).
     *
     * @return void
     */
    public function test_show()
    {
        $zone = Zone::factory()->create();

        $response = $this->getJson("/api/zones/{$zone->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $zone->id,
                         'name' => $zone->name
                     ]
                 ]);
    }

    /**
     * Test para actualizar una zona existente (update).
     *
     * @return void
     */
    public function test_update()
    {
        $zone = Zone::factory()->create();

        $updatedData = [
            'name' => 'Updated Zone Name',
            'description' => 'Updated description for zone'
        ];

        $response = $this->putJson("/api/zones/{$zone->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'name' => 'Updated Zone Name',
                         'description' => 'Updated description for zone'
                     ]
                 ]);
        $this->assertDatabaseHas('zones', $updatedData);
    }

    /**
     * Test para eliminar una zona (destroy).
     *
     * @return void
     */
    public function test_destroy()
    {
        $zone = Zone::factory()->create();

        $response = $this->deleteJson("/api/zones/{$zone->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('zones', ['id' => $zone->id]);
    }

    /**
     * Test para obtener los operadores de una zona (getOperators).
     *
     * @return void
     */
    public function test_getOperators()
    {
        $zone = Zone::factory()->create();

        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $zone->users()->attach($user->id); // Relacionar los usuarios con la zona
        }

        $response = $this->getJson("/api/zones/{$zone->id}/operators");

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data'); // Verificar que haya 3 operadores
    }
}
