@extends('layouts.app')

@section('title', 'Inventory Item')
@section('header', $item->name)
@section('subtitle', 'Inventory details and history')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 card-spiritual p-6">
        <div class="mb-6 pb-6 border-b border-gray-200">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Category</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $item->category }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Unit</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $item->unit }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Current Stock</p>
                    <p class="text-2xl font-bold text-saffron mt-1">{{ $item->quantity }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Reorder Level</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $item->reorder_level ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Unit Price</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">Rp{{ number_format($item->purchase_price ?? 0, 0) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Total Value</p>
                    <p class="text-lg font-medium text-gold mt-1">Rp{{ number_format($item->total_value ?? 0, 0) }}</p>
                </div>
            </div>
        </div>

        @if($item->description)
            <div class="mb-6">
                <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Description</p>
                <p class="text-gray-700">{{ $item->description }}</p>
            </div>
        @endif

        @if($item->notes)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-xs font-semibold text-blue-900 uppercase mb-2">Notes</p>
                <p class="text-blue-800">{{ $item->notes }}</p>
            </div>
        @endif
    </div>

    <div>
        <div class="card-spiritual p-6 mb-6">
            <h3 class="text-lg font-semibold text-deep-brown mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="<?php echo e(url('inventory/'.$item->id.'/edit')); ?>" class="block w-full px-4 py-2 btn-spiritual text-white rounded-lg text-center text-sm font-medium">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ url('inventory/'.$item->id) }}" method="POST" onsubmit="return confirm('Delete this item?');" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="block w-full px-4 py-2 bg-red-600 text-white rounded-lg text-center text-sm font-medium">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form>
                <button onclick="openAdjustForm()" class="block w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-center text-sm font-medium hover:bg-blue-200">
                    <i class="fas fa-plus"></i> Adjust Stock
                </button>
            </div>
        </div>

        <div id="adjustForm" class="card-spiritual p-6 hidden">
            <h3 class="text-lg font-semibold text-deep-brown mb-4">Adjust Stock</h3>
            <form action="{{ url('inventory/'.$item->id.'/adjust-stock') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-deep-brown mb-2">Quantity Change</label>
                    <input type="number" name="quantity_change" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="Positive or negative">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-deep-brown mb-2">Reason</label>
                    <input type="text" name="reason" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="e.g., Stock received, Damaged, Used">
                </div>
                <button type="submit" class="w-full btn-spiritual px-4 py-2 text-white rounded-lg font-medium">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openAdjustForm() {
    document.getElementById('adjustForm').classList.toggle('hidden');
}
</script>
@endsection
