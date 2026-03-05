@extends('layouts.app')

@section('title', 'Record Donation')
@section('header', 'Record New Donation')
@section('subtitle', 'Add a new donation to the temple')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
        <form action="{{ route('donations.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Member -->
                <div class="md:col-span-2">
                    <label for="member_id" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user text-saffron"></i> Member (Optional)
                    </label>
                    <select name="member_id" id="member_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="">-- Anonymous Donation --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fund Category -->
                <div class="md:col-span-2">
                    <label for="fund_category_id" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sitemap text-saffron"></i> Fund Category *
                    </label>
                    <select name="fund_category_id" id="fund_category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="">-- Select Category --</option>
                        @foreach($fundCategories as $category)
                            <option value="{{ $category->id }}" {{ old('fund_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('fund_category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-coins text-saffron"></i> Amount (Rp) *
                    </label>
                    <input type="number" name="amount" id="amount" required step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('amount') }}">
                    @error('amount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Donation Method -->
                <div>
                    <label for="donation_method" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-credit-card text-saffron"></i> Method *
                    </label>
                    <select name="donation_method" id="donation_method" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="cash" {{ old('donation_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="qris" {{ old('donation_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="bank_transfer" {{ old('donation_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="virtual" {{ old('donation_method') == 'virtual' ? 'selected' : '' }}>Virtual Payment</option>
                        <option value="check" {{ old('donation_method') == 'check' ? 'selected' : '' }}>Check</option>
                    </select>
                    @error('donation_method')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction ID -->
                <div>
                    <label for="transaction_id" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-receipt text-saffron"></i> Transaction ID
                    </label>
                    <input type="text" name="transaction_id" id="transaction_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('transaction_id') }}">
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes') }}</textarea>
                </div>

                <!-- Is Regular -->
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_regular" id="is_regular" value="1" {{ old('is_regular') ? 'checked' : '' }}>
                        <span class="text-sm font-semibold text-deep-brown">
                            <i class="fas fa-repeat text-saffron"></i> This is a recurring donation
                        </span>
                    </label>
                </div>

                <!-- Frequency (shown if is_regular) -->
                <div id="frequency-section" class="md:col-span-2" style="display: {{ old('is_regular') ? 'block' : 'none' }};">
                    <label for="frequency" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-calendar text-saffron"></i> Frequency
                    </label>
                    <select name="frequency" id="frequency"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ old('frequency') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Record Donation
                </button>
                <a href="{{ route('donations.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('is_regular').addEventListener('change', function() {
    document.getElementById('frequency-section').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
