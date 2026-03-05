<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Member;

class MemberCrudTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_create_edit_and_delete_member()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // creation
        $response = $this->post(route('members.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'member',
        ]);
        $member = Member::first();
        $response->assertRedirect(route('members.show', ['member' => $member->id]));

        // update
        $response = $this->put(route('members.update', ['member' => $member->id]), [
            'name' => 'John Updated',
            'email' => 'johnupdated@example.com',
            'role' => 'member',
        ]);
        $response->assertRedirect(route('members.show', ['member' => $member->id]));
        $this->assertDatabaseHas('users', ['email' => 'johnupdated@example.com']);

        // delete
        $response = $this->delete(route('members.destroy', ['member' => $member->id]));
        $response->assertRedirect(route('members.index'));
        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }
}
