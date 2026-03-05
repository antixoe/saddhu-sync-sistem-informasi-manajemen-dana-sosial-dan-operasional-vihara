<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\FundCategory;
use App\Models\Donation;

class PublicDonationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function public_donation_form_is_accessible()
    {
        $response = $this->get(route('donate'));
        $response->assertStatus(200);
        $response->assertSee('Make a Donation');
    }

    /** @test */
    public function submitting_public_donation_records_the_entry()
    {
        $category = FundCategory::create(['name' => 'General', 'description' => '']);

        $response = $this->post(route('donate.store'), [
            'fund_category_id' => $category->id,
            'amount' => 50000,
            'donation_method' => 'qris',
        ]);

        $response->assertRedirect(route('donate.thankyou'));

        $this->assertDatabaseHas('donations', [
            'fund_category_id' => $category->id,
            'amount' => 50000,
            'donation_method' => 'qris',
        ]);
    }

    /** @test */
    public function thankyou_page_shows_message()
    {
        $response = $this->get(route('donate.thankyou'));
        $response->assertStatus(200);
        $response->assertSee('Thank You');
    }
}
