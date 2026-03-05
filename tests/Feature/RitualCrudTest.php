<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ritual;

class RitualCrudTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_create_update_and_delete_ritual()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('rituals.store'), [
            'title' => 'Test Ritual',
            'type' => 'prayer',
            'start_time' => now()->format('Y-m-d'),
        ]);

        $ritual = Ritual::first();
        $response->assertRedirect(route('rituals.show', ['ritual' => $ritual->id]));

        $response = $this->get(route('rituals.edit', ['ritual' => $ritual->id]));
        $response->assertStatus(200);

        $response = $this->put(route('rituals.update', ['ritual' => $ritual->id]), [
            'title' => 'Updated Ritual',
            'type' => 'prayer',
            'start_time' => now()->addDay()->format('Y-m-d'),
        ]);
        $response->assertRedirect(route('rituals.show', ['ritual' => $ritual->id]));
        $this->assertDatabaseHas('rituals', ['title' => 'Updated Ritual']);

        $response = $this->delete(route('rituals.destroy', ['ritual' => $ritual->id]));
        $response->assertRedirect(route('rituals.index'));
        $this->assertDatabaseMissing('rituals', ['id' => $ritual->id]);
    }
}
