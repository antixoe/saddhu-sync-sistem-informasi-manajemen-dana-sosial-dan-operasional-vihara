@extends('layouts.app')

@section('title', 'Donate')
@section('header', 'Make a Donation')
@section('subtitle', 'Support the vihara through QRIS, bank transfer or other virtual payments')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Instructions pulled from settings --}}
        @if($qrCode)
            <div class="card-spiritual p-6 text-center">
                <h3 class="font-semibold mb-2">Scan QR Code</h3>
                <img src="{{ $qrCode }}" alt="Donation QR code" class="mx-auto max-h-64">
            </div>
        @endif

        @if($bankDetails)
            <div class="card-spiritual p-6">
                <h3 class="font-semibold mb-2">Bank Account</h3>
                <p class="whitespace-pre-line">{!! nl2br(e($bankDetails)) !!}</p>
            </div>
        @endif

        @if($virtualAccounts)
            <div class="card-spiritual p-6">
                <h3 class="font-semibold mb-2">Other Virtual Methods</h3>
                <p class="whitespace-pre-line">{!! nl2br(e($virtualAccounts)) !!}</p>
            </div>
        @endif

        {{-- Donation submission form --}}
        <div class="card-spiritual p-8">
            <form action="{{ route('donate.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
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

                <div>
                    <label for="donation_method" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-credit-card text-saffron"></i> Method *
                    </label>
                    <select name="donation_method" id="donation_method" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="qris" {{ old('donation_method') == 'qris' ? 'selected' : '' }}>QRIS / QR Code</option>
                        <option value="bank_transfer" {{ old('donation_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="virtual" {{ old('donation_method') == 'virtual' ? 'selected' : '' }}>Other Virtual Payment</option>
                        <option value="cash" {{ old('donation_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                    @error('donation_method')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transaction_id" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-receipt text-saffron"></i> Transaction ID / Reference
                    </label>
                    <input type="text" name="transaction_id" id="transaction_id"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                           value="{{ old('transaction_id') }}">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Additional Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes') }}</textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="px-6 py-2 rounded-lg bg-saffron text-white">
                        Submit Donation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
