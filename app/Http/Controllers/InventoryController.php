<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $category = $request->input('category');
        $query = InventoryItem::latest();

        if ($category) {
            $query->where('category', $category);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $items = $query->paginate(20)->withQueryString();
        $lowStockItems = InventoryItem::whereNotNull('reorder_level')
            ->whereRaw('quantity <= reorder_level')
            ->count();
        
        // Get all unique categories
        $categories = InventoryItem::distinct()->pluck('category')->sort()->filter()->values();

        return view('inventory.index', [
            'items' => $items,
            'lowStockCount' => $lowStockItems,
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('inventory.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $item = InventoryItem::create($validated);
        ActivityLog::log('created', 'InventoryItem', $item->id, "New inventory item '{$item->name}' added");

        return redirect()->route('inventory.show', $item)->with('success', 'Inventory item created successfully!');
    }

    public function show(InventoryItem $inventory): View
    {
        return view('inventory.show', ['item' => $inventory]);
    }

    public function edit(InventoryItem $inventory): View
    {
        return view('inventory.edit', ['item' => $inventory]);
    }

    public function update(Request $request, InventoryItem $inventory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $inventory->toArray();
        $inventory->update($validated);
        ActivityLog::log('updated', 'InventoryItem', $inventory->id, "Inventory item updated", $oldValues);

        return redirect()->route('inventory.show', $inventory)->with('success', 'Inventory item updated successfully!');
    }

    public function adjustStock(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $validated = $request->validate([
            'quantity_change' => 'required|integer',
            'reason' => 'required|string',
        ]);

        $oldQuantity = $inventoryItem->quantity;
        $inventoryItem->update([
            'quantity' => $inventoryItem->quantity + $validated['quantity_change'],
            'last_updated_at' => now(),
        ]);

        ActivityLog::log('updated', 'InventoryItem', $inventoryItem->id,
            "Stock adjusted for {$inventoryItem->name}: {$oldQuantity} -> {$inventoryItem->quantity} ({$validated['reason']})");

        return back()->with('success', 'Stock adjusted successfully!');
    }

    public function destroy(InventoryItem $inventory): RedirectResponse
    {
        ActivityLog::log('deleted', 'InventoryItem', $inventory->id, "Inventory item '{$inventory->name}' deleted");
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully!');
    }
}
