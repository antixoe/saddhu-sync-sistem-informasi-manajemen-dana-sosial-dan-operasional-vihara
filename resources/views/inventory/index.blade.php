@extends('layouts.app')

@section('title', 'Inventory')
@section('header', 'Inventory Management')
@section('subtitle', 'Track temple supplies and equipment')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-4">
        <div class="card-spiritual px-4 py-2 inline-block">
            <span class="font-semibold text-deep-brown">Total Items: </span>
            <span class="text-saffron font-bold">{{ $items->total() }}</span>
        </div>
        @if($lowStockCount > 0)
            <div class="card-spiritual px-4 py-2 inline-block bg-yellow-50 border-yellow-300">
                <span class="font-semibold text-deep-brown">Low Stock: </span>
                <span class="text-rust font-bold">{{ $lowStockCount }}</span>
            </div>
        @endif
    </div>
            <div class="flex items-center space-x-3">
                <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm" onchange="filterByCategory(this.value)">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <form method="GET" action="{{ route('inventory.index') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="category" value="{{ request('category') }}" />
                    <input type="text" name="q" placeholder="Search inventory..." value="{{ request('q') }}" class="px-3 py-2 border border-gray-300 rounded-l-md text-sm" />
                    <button type="submit" class="px-3 py-2 bg-saffron text-white rounded-r-md text-sm"><i class="fas fa-search"></i></button>
                </form>
            </div>
        <button onclick="openModal('createInventoryModal')" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add Item</span>
        </button>
</div>

<div class="card-spiritual overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Item Name</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Category</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Quantity</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Unit Value</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Status</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 font-medium text-deep-brown">{{ $item->name }}</td>
                        <td class="py-4 px-6 text-gray-600">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">
                                {{ $item->category }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center font-semibold">{{ $item->quantity }} {{ $item->unit }}</td>
                        <td class="py-4 px-6 text-right text-gray-600">Rp{{ number_format($item->purchase_price ?? 0, 0) }}</td>
                        <td class="py-4 px-6 text-center">
                            @if($item->isLowStock())
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">Low Stock</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">In Stock</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button onclick="openInventoryModal({{ $item->id }})" class="text-saffron hover:text-rust text-sm font-medium">View</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 px-6 text-center text-gray-600">No inventory items</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $items->links() }}
</div>

<!-- create inventory modal -->
<div id="createInventoryModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white rounded-lg w-11/12 max-w-3xl p-8 relative overflow-auto max-h-[90vh]">
        <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('createInventoryModal')">&times;</button>
        <h2 class="text-2xl font-semibold text-deep-brown mb-4">Add New Inventory Item</h2>
        <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-box text-saffron"></i> Item Name *
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-tags text-saffron"></i> Category *
                    </label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="">-- Select Category --</option>
                        <option value="ritual_items" {{ old('category') == 'ritual_items' ? 'selected' : '' }}>Ritual Items (Rupang)</option>
                        <option value="supplies" {{ old('category') == 'supplies' ? 'selected' : '' }}>Supplies</option>
                        <option value="incense" {{ old('category') == 'incense' ? 'selected' : '' }}>Incense & Offerings</option>
                        <option value="paper" {{ old('category') == 'paper' ? 'selected' : '' }}>Paper Products</option>
                        <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-balance-scale text-saffron"></i> Unit *
                    </label>
                    <input type="text" name="unit" id="unit" required placeholder="e.g., piece, kg, box, liter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('unit') }}">
                    @error('unit')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-hashtag text-saffron"></i> Quantity *
                    </label>
                    <input type="number" name="quantity" id="quantity" required min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('quantity', 0) }}">
                    @error('quantity')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Purchase Price -->
                <div>
                    <label for="purchase_price" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-tag text-saffron"></i> Unit Price (Rp)
                    </label>
                    <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('purchase_price') }}">
                </div>

                <!-- Reorder Level -->
                <div>
                    <label for="reorder_level" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-exclamation-triangle text-saffron"></i> Reorder Level
                    </label>
                    <input type="number" name="reorder_level" id="reorder_level" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('reorder_level') }}"
                        placeholder="Alert when quantity falls below this">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-align-left text-saffron"></i> Description
                    </label>
                    <textarea name="description" id="description" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('description') }}</textarea>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Notes
                    </label>
                    <textarea name="notes" id="notes" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Add Item
                </button>
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50" onclick="closeModal('createInventoryModal')">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function filterByCategory(categoryValue) {
    let url = new URL(window.location);
    if (categoryValue) {
        url.searchParams.set('category', categoryValue);
    } else {
        url.searchParams.delete('category');
    }
    window.location = url.toString();
}

// Inventory view modal
function openInventoryModal(itemId) {
    const modal_content = document.getElementById('inventoryDetailsContent');
    
    fetch(`/api/inventory/${itemId}`)
        .then(r => r.json())
        .then(data => {
            modal_content.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Item Name</h3>
                        <p class="text-deep-brown">${data.name}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Category</h3>
                        <p class="text-deep-brown">${data.category}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Description</h3>
                        <p class="text-gray-600">${data.description || 'N/A'}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Quantity</h3>
                        <p class="text-deep-brown font-bold">${data.quantity} ${data.unit}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Unit Value</h3>
                        <p class="text-deep-brown">Rp${data.purchase_price ? parseInt(data.purchase_price).toLocaleString('id-ID') : 'N/A'}</p>
                    </div>
                    ${data.reorder_level ? `<div>
                        <h3 class="text-sm font-semibold text-gray-700">Reorder Level</h3>
                        <p class="text-deep-brown">${data.reorder_level}</p>
                    </div>` : ''}
                    ${data.notes ? `<div>
                        <h3 class="text-sm font-semibold text-gray-700">Notes</h3>
                        <p class="text-gray-600">${data.notes}</p>
                    </div>` : ''}
                </div>
            `;
        })
        .catch(err => {
            modal_content.innerHTML = '<p class="text-red-600">Error loading inventory details</p>';
            console.error(err);
        });
    
    openModal('viewInventoryModal');
}
</script>

<!-- View Inventory Modal -->
<div id="viewInventoryModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white rounded-lg w-11/12 max-w-lg p-8 relative">
        <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('viewInventoryModal')">&times;</button>
        <h2 class="text-2xl font-semibold text-deep-brown mb-4">Inventory Item Details</h2>
        <div id="inventoryDetailsContent">
            <p class="text-gray-600">Loading...</p>
        </div>
    </div>
</div>
@endsection
