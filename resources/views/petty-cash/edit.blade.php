@extends('layouts.app')

@section('title', 'Edit Petty Cash')
@section('header', 'Edit Petty Cash Transaction')
@section('subtitle', 'Modify transaction details')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
        <form action="{{ route('petty-cash.update', $transaction) }}" method="POST" class="space-y-6">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-list text-saffron"></i> Category *
                    </label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="">-- Select Category --</option>
                        <option value="transportation" {{ old('category', $transaction->category) == 'transportation' ? 'selected' : '' }}>Transportation</option>
                        <option value="food" {{ old('category', $transaction->category) == 'food' ? 'selected' : '' }}>Food & Beverages</option>
                        <option value="office_supplies" {{ old('category', $transaction->category) == 'office_supplies' ? 'selected' : '' }}>Office Supplies</option>
                        <option value="maintenance" {{ old('category', $transaction->category) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="utilities" {{ old('category', $transaction->category) == 'utilities' ? 'selected' : '' }}>Utilities</option>
                        <option value="communication" {{ old('category', $transaction->category) == 'communication' ? 'selected' : '' }}>Communication</option>
                        <option value="miscellaneous" {{ old('category', $transaction->category) == 'miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                    </select>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="transaction_date" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-calendar text-saffron"></i> Date *
                    </label>
                    <input type="date" name="transaction_date" id="transaction_date" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}">
                    @error('transaction_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-coins text-saffron"></i> Amount (Rp) *
                    </label>
                    <input type="number" name="amount" id="amount" required step="0.01" min="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('amount', $transaction->amount) }}">
                    @error('amount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-credit-card text-saffron"></i> Payment Method *
                    </label>
                    <select name="payment_method" id="payment_method" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ old('payment_method', $transaction->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="online_transfer" {{ old('payment_method', $transaction->payment_method) == 'online_transfer' ? 'selected' : '' }}>Online Transfer</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-align-left text-saffron"></i> Description *
                    </label>
                    <input type="text" name="description" id="description" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('description', $transaction->description) }}"
                        placeholder="What was purchased?">
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Additional Notes
                    </label>
                    <textarea name="notes" id="notes" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes', $transaction->notes) }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Update Transaction
                </button>
                <a href="{{ route('petty-cash.show', $transaction) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
