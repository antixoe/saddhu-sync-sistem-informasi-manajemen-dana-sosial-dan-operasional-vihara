@extends('layouts.app')

@section('title', 'Add Inventory')
@section('header', 'Add New Inventory Item')
@section('subtitle', 'Register a new item in the temple inventory')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
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
                <a href="{{ route('inventory.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
