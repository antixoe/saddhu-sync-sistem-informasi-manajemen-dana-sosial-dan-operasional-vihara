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
            // new address/contact fields
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'postal_code' => '12345',
            'address' => '123 Test Lane',
            'latitude' => '-6.2000000',
            'longitude' => '106.8166660',
            'contact_name' => 'John Doe',
            'contact_phone' => '081234567890',
        ]);

        $response->assertRedirect(route('donate.thankyou'));

        $this->assertDatabaseHas('donations', [
            'fund_category_id' => $category->id,
            'amount' => 50000,
            'donation_method' => 'qris',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'postal_code' => '12345',
            'address' => '123 Test Lane',
            'latitude' => '-6.2000000',
            'longitude' => '106.8166660',
            'contact_name' => 'John Doe',
            'contact_phone' => '081234567890',
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
