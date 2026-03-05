<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\PettyCash;

class PettyCashCrudTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_perform_petty_cash_crud()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('petty-cash.store'), [
            'category' => 'transportation',
            'amount' => 15000,
            'description' => 'Taxi fare',
            'transaction_date' => today()->format('Y-m-d'),
            'payment_method' => 'cash',
        ]);

        $transaction = PettyCash::first();
        $response->assertRedirect(route('petty-cash.index'));
        $this->assertDatabaseHas('petty_cash', ['description' => 'Taxi fare']);

        // show
        $response = $this->get(route('petty-cash.show', ['petty_cash' => $transaction->id]));
        $response->assertStatus(200);

        // edit
        $response = $this->get(route('petty-cash.edit', ['petty_cash' => $transaction->id]));
        $response->assertStatus(200);

        $response = $this->put(route('petty-cash.update', ['petty_cash' => $transaction->id]), [
            'category' => 'transportation',
            'amount' => 20000,
            'description' => 'Taxi fare updated',
            'transaction_date' => today()->format('Y-m-d'),
            'payment_method' => 'cash',
        ]);
        $response->assertRedirect(route('petty-cash.index'));
        $this->assertDatabaseHas('petty_cash', ['description' => 'Taxi fare updated']);

        // delete
        $response = $this->delete(route('petty-cash.destroy', ['petty_cash' => $transaction->id]));
        $response->assertRedirect(route('petty-cash.index'));
        $this->assertDatabaseMissing('petty_cash', ['id' => $transaction->id]);
    }
}
