@extends('layouts.app')

@section('title', 'Edit Inventory Item')
@section('header', 'Edit Item: ' . $item->name)
@section('subtitle', 'Update item information')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
        <form action="{{ route('inventory.update', $item) }}" method="POST" class="space-y-6">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-deep-brown mb-2">Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('name', $item->name) }}">
                </div>

                <div>
                    <label for="category" class="block text-sm font-semibold text-deep-brown mb-2">Category</label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="Altar Supplies" {{ $item->category == 'Altar Supplies' ? 'selected' : '' }}>Altar Supplies</option>
                        <option value="Cleaning Equipment" {{ $item->category == 'Cleaning Equipment' ? 'selected' : '' }}>Cleaning Equipment</option>
                        <option value="Office Supplies" {{ $item->category == 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                        <option value="Ritual Accessories" {{ $item->category == 'Ritual Accessories' ? 'selected' : '' }}>Ritual Accessories</option>
                        <option value="Food & Offering" {{ $item->category == 'Food & Offering' ? 'selected' : '' }}>Food & Offering</option>
                        <option value="Miscellaneous" {{ $item->category == 'Miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                    </select>
                </div>

                <div>
                    <label for="unit" class="block text-sm font-semibold text-deep-brown mb-2">Unit Type</label>
                    <input type="text" name="unit" id="unit" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('unit', $item->unit) }}">
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-semibold text-deep-brown mb-2">Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('quantity', $item->quantity) }}">
                </div>

                <div>
                    <label for="purchase_price" class="block text-sm font-semibold text-deep-brown mb-2">Purchase Price</label>
                    <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('purchase_price', $item->purchase_price) }}">
                </div>

                <div>
                    <label for="reorder_level" class="block text-sm font-semibold text-deep-brown mb-2">Reorder Level</label>
                    <input type="number" name="reorder_level" id="reorder_level" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('reorder_level', $item->reorder_level) }}">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-deep-brown mb-2">Description</label>
                    <textarea name="description" id="description" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('description', $item->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes', $item->notes) }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
                <a href="{{ route('inventory.show', $item) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection