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
        $query = InventoryItem::latest();

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

        return view('inventory.index', [
            'items' => $items,
            'lowStockCount' => $lowStockItems,
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

    public function show(InventoryItem $item): View
    {
        return view('inventory.show', ['item' => $item]);
    }

    public function edit(InventoryItem $item): View
    {
        return view('inventory.edit', ['item' => $item]);
    }

    public function update(Request $request, InventoryItem $item): RedirectResponse
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

        $oldValues = $item->toArray();
        $item->update($validated);
        ActivityLog::log('updated', 'InventoryItem', $item->id, "Inventory item updated", $oldValues);

        return redirect()->route('inventory.show', $item)->with('success', 'Inventory item updated successfully!');
    }

    public function adjustStock(Request $request, InventoryItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'quantity_change' => 'required|integer',
            'reason' => 'required|string',
        ]);

        $oldQuantity = $item->quantity;
        $item->update([
            'quantity' => $item->quantity + $validated['quantity_change'],
            'last_updated_at' => now(),
        ]);

        ActivityLog::log('updated', 'InventoryItem', $item->id,
            "Stock adjusted for {$item->name}: {$oldQuantity} -> {$item->quantity} ({$validated['reason']})");

        return back()->with('success', 'Stock adjusted successfully!');
    }

    public function destroy(InventoryItem $item): RedirectResponse
    {
        ActivityLog::log('deleted', 'InventoryItem', $item->id, "Inventory item '{$item->name}' deleted");
        $item->delete();

        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully!');
    }
}
