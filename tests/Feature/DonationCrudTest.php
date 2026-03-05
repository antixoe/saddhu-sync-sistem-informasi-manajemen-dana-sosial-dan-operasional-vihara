<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\FundCategory;
use App\Models\Donation;

class DonationCrudTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_edit_and_delete_donation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = FundCategory::create(['name' => 'General', 'description' => '']);

        $response = $this->post(route('donations.store'), [
            'fund_category_id' => $category->id,
            'amount' => 100000,
            'donation_method' => 'cash',
        ]);
        $donation = Donation::first();
        $response->assertRedirect(route('donations.show', ['donation' => $donation->id]));

        // update
        $response = $this->put(route('donations.update', ['donation' => $donation->id]), [
            'fund_category_id' => $category->id,
            'amount' => 120000,
            'donation_method' => 'cash',
        ]);
        $response->assertRedirect(route('donations.show', ['donation' => $donation->id]));
        $this->assertDatabaseHas('donations', ['amount' => 120000]);

        // delete
        $response = $this->delete(route('donations.destroy', ['donation' => $donation->id]));
        $response->assertRedirect(route('donations.index'));
        $this->assertDatabaseMissing('donations', ['id' => $donation->id]);
    }
}
