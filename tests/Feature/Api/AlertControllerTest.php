<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_alerts()
    {
        $user = User::factory()->create();
        Alert::factory()->count(3)->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/alerts');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id', 'message', 'created_at', 'updated_at'
                         ]
                     ],
                     'links' => ['first', 'last', 'prev', 'next'],
                     'meta' => ['current_page', 'from', 'last_page', 'total']
                 ]);
    }

    public function test_store_alert()
    {
        $user = User::factory()->create();
        $alertData = [
            'message' => 'New alert message',
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/alerts', $alertData);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => $alertData
                 ]);
    }

    public function test_show_alert()
    {
        $user = User::factory()->create();
        $alert = Alert::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/alerts/' . $alert->id);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'id' => $alert->id,
                         'message' => $alert->message,
                         'created_at' => $alert->created_at->toISOString(),
                         'updated_at' => $alert->updated_at->toISOString(),
                     ]
                 ]);
    }

    public function test_update_alert()
    {
        $user = User::factory()->create();
        $alert = Alert::factory()->create();
        $updatedData = [
            'message' => 'Updated alert message',
        ];

        $response = $this->actingAs($user, 'api')->putJson('/api/alerts/' . $alert->id, $updatedData);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => $updatedData
                 ]);
    }

    public function test_delete_alert()
    {
        $user = User::factory()->create();
        $alert = Alert::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson('/api/alerts/' . $alert->id);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Alerta eliminada con éxito'
                 ]);

        $this->assertDatabaseMissing('alerts', ['id' => $alert->id]);
    }
}
