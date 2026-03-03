@extends('layouts.app')

@section('title', 'Donation Details')
@section('header', 'Donation #' . $donation->id)
@section('subtitle', 'Donation details and receipt')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card-spiritual p-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 pb-8 border-b border-gray-200">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Amount</p>
                    <p class="text-2xl font-bold text-saffron mt-2">Rp{{ number_format($donation->amount, 0) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Category</p>
                    <p class="text-lg font-medium text-deep-brown mt-2">{{ $donation->fundCategory->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Method</p>
                    <p class="text-lg font-medium text-deep-brown mt-2 uppercase">{{ $donation->donation_method }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Status</p>
                    <p class="mt-2">
                        @if($donation->verified_at)
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Verified</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Pending</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase">Donor</label>
                    <p class="text-lg text-deep-brown font-medium mt-1">
                        {{ $donation->is_anonymous ? 'Anonymous' : ($donation->member->user->name ?? 'N/A') }}
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase">Donated On</label>
                    <p class="text-lg text-deep-brown font-medium mt-1">{{ $donation->donated_at->format('l, F d, Y g:i A') }}</p>
                </div>
                @if($donation->transaction_id)
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase">Transaction ID</label>
                        <p class="text-lg text-deep-brown font-mono font-medium mt-1">{{ $donation->transaction_id }}</p>
                    </div>
                @endif
                @if($donation->notes)
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase">Notes</label>
                        <p class="text-gray-700 mt-1">{{ $donation->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div>
        <div class="card-spiritual p-6">
            <h3 class="text-lg font-semibold text-deep-brown mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('donations.edit', $donation) }}" class="block w-full px-4 py-2 btn-spiritual text-white rounded-lg text-center text-sm font-medium">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @if(!$donation->verified_at)
                    <form action="{{ route('donations.verify', $donation) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">
                            <i class="fas fa-check"></i> Verify
                        </button>
                    </form>
                @endif
                @if(!$donation->receipt_sent)
                    <form action="{{ route('donations.send-receipt', $donation) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200">
                            <i class="fas fa-envelope"></i> Send Receipt
                        </button>
                    </form>
                @else
                    <div class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium text-center">
                        <i class="fas fa-check-circle"></i> Receipt Sent
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
