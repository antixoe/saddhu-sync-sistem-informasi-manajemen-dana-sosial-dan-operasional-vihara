<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\InventoryItem;

class InventoryCrudTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_perform_full_crud_on_inventory()
    {
        $user = User::factory()->create();
        $this->actingAs($user);


        // create via POST and verify it was stored
        $response = $this->post(route('inventory.store'), [
            'name' => 'Test Item',
            'category' => 'supplies',
            'unit' => 'piece',
            'quantity' => 10,
        ]);
        $this->assertDatabaseHas('inventory_items', ['name' => 'Test Item']);
        // the controller currently redirects to the newly created item's show page
        $newItem = InventoryItem::first();
        $response->assertRedirect(route('inventory.show', ['inventory' => $newItem->id]));

        // grab an existing item for the next steps; using factory ensures we're
        // reading from the same connection that will be used by subsequent
        // GET/PUT/DELETE requests, avoiding issues with sqlite memory databases.
        $item = InventoryItem::first();
        $this->assertNotNull($item, 'Inventory item should exist for further assertions');

        // show
        $response = $this->get(route('inventory.show', ['inventory' => $item->id]));
        $response->assertStatus(200)->assertSee('Test Item');

        // edit page
        $response = $this->get(route('inventory.edit', ['inventory' => $item->id]));
        $response->assertStatus(200);

        // update
        $response = $this->put(route('inventory.update', ['inventory' => $item->id]), [
            'name' => 'Updated Item',
            'category' => 'supplies',
            'unit' => 'piece',
            'quantity' => 5,
        ]);
        $response->assertRedirect(route('inventory.show', ['inventory' => $item->id]));
        $this->assertDatabaseHas('inventory_items', ['name' => 'Updated Item']);

        // destroy
        $response = $this->delete(route('inventory.destroy', ['inventory' => $item->id]));
        $response->assertRedirect(route('inventory.index'));
        $this->assertDatabaseMissing('inventory_items', ['id' => $item->id]);
    }
}
