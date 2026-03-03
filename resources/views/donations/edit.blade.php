@extends('layouts.app')

@section('title', 'Edit Donation')
@section('header', 'Edit Donation')
@section('subtitle', 'Update donation information')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
        <form action="{{ route('donations.update', $donation) }}" method="POST" class="space-y-6">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fund Category -->
                <div class="md:col-span-2">
                    <label for="fund_category_id" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sitemap text-saffron"></i> Fund Category
                    </label>
                    <select name="fund_category_id" id="fund_category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        @foreach($fundCategories as $category)
                            <option value="{{ $category->id }}" {{ $donation->fund_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-coins text-saffron"></i> Amount (Rp)
                    </label>
                    <input type="number" name="amount" id="amount" required step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('amount', $donation->amount) }}">
                </div>

                <!-- Donation Method -->
                <div>
                    <label for="donation_method" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-credit-card text-saffron"></i> Method
                    </label>
                    <select name="donation_method" id="donation_method" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="cash" {{ $donation->donation_method == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="qris" {{ $donation->donation_method == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="bank_transfer" {{ $donation->donation_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="check" {{ $donation->donation_method == 'check' ? 'selected' : '' }}>Check</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes', $donation->notes) }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
                <a href="{{ route('donations.show', $donation) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
