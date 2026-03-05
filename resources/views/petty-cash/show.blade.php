@extends('layouts.app')

@section('title','Petty Cash Transaction')
@section('header','Petty Cash Details')
@section('subtitle','Transaction information and options')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card-spiritual p-8 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Date</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $transaction->transaction_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Category</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $transaction->category }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Amount</p>
                    <p class="text-2xl font-bold text-saffron mt-1">Rp{{ number_format($transaction->amount,0) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Payment Method</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ ucfirst(str_replace('_',' ',$transaction->payment_method)) }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold text-gray-600 uppercase">Description</p>
                    <p class="text-gray-700 mt-1">{{ $transaction->description }}</p>
                </div>
                @if($transaction->notes)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Notes</p>
                        <p class="text-gray-700 mt-1">{{ $transaction->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex space-x-4">
            <a href="{{ route('petty-cash.edit', $transaction) }}" class="px-4 py-2 btn-spiritual text-white rounded-lg">Edit</a>
            <form action="{{ route('petty-cash.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Delete this transaction?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
            </form>
            <a href="{{ route('petty-cash.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700">Back</a>
        </div>
    </div>
@endsection
