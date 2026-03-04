@extends('layouts.app')

@section('title', 'Petty Cash')
@section('header', 'Petty Cash Management')
@section('subtitle', 'Track daily small expenses and cash flow')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="card-spiritual p-6">
        <p class="text-gray-600 text-sm">Today\'s Total</p>
        <p class="text-3xl font-bold text-deep-brown mt-2">Rp{{ number_format($totalToday, 0) }}</p>
    </div>
    <div class="card-spiritual p-6">
        <p class="text-gray-600 text-sm">This Month</p>
        <p class="text-3xl font-bold text-deep-brown mt-2">Rp{{ number_format($totalMonth, 0) }}</p>
    </div>
    <div class="card-spiritual p-6">
        <p class="text-gray-600 text-sm">Transactions</p>
        <p class="text-3xl font-bold text-deep-brown mt-2">{{ $transactions->total() }}</p>
    </div>
</div>

<div class="flex justify-end mb-6 items-center space-x-4">
    <div class="flex items-center space-x-3">
        <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm" onchange="filterByCategory(this.value)">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <form method="GET" action="{{ route('petty-cash.index') }}" class="flex items-center gap-0">
            <input type="hidden" name="category" value="{{ request('category') }}" />
            <input type="text" name="q" placeholder="Search petty cash..." value="{{ request('q') }}" class="px-3 py-2 border border-gray-300 rounded-l-md text-sm" />
            <button type="submit" class="px-3 py-2 bg-saffron text-white text-sm border-l border-orange-400"><i class="fas fa-search"></i></button>
            <a href="{{ route('petty-cash.index') }}" class="px-3 py-2 bg-gray-400 text-white rounded-r-md text-sm hover:bg-gray-500"><i class="fas fa-times"></i></a>
        </form>
    </div>
    <button onclick="openModal('createPettyModal')" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Add Transaction</span>
    </button>
</div>

<div class="card-spiritual overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Date</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Category</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Description</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">By</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Amount</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Method</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-gray-600">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded font-medium">
                                {{ $transaction->category }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-gray-700">{{ $transaction->description }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ $transaction->user->name ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-right font-semibold text-deep-brown">Rp{{ number_format($transaction->amount, 0) }}</td>
                        <td class="py-4 px-6 text-center text-xs text-gray-600 uppercase">{{ $transaction->payment_method }}</td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('petty-cash.edit', $transaction) }}" class="text-saffron hover:text-rust text-sm font-medium">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-gray-600">No petty cash transactions</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $transactions->links() }}
</div>

<!-- create petty cash modal -->
<div id="createPettyModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white rounded-lg w-11/12 max-w-3xl p-8 relative overflow-auto max-h-[90vh]">
        <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('createPettyModal')">&times;</button>
        <h2 class="text-2xl font-semibold text-deep-brown mb-4">Record Petty Cash Transaction</h2>
        <form action="{{ route('petty-cash.store') }}" method="POST" class="space-y-6">
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
                        <option value="transportation" {{ old('category') == 'transportation' ? 'selected' : '' }}>Transportation</option>
                        <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>Food & Beverages</option>
                        <option value="office_supplies" {{ old('category') == 'office_supplies' ? 'selected' : '' }}>Office Supplies</option>
                        <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>Utilities</option>
                        <option value="communication" {{ old('category') == 'communication' ? 'selected' : '' }}>Communication</option>
                        <option value="miscellaneous" {{ old('category') == 'miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
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
                        value="{{ old('transaction_date', today()->format('Y-m-d')) }}">
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
                        value="{{ old('amount') }}">
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
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="online_transfer" {{ old('payment_method') == 'online_transfer' ? 'selected' : '' }}>Online Transfer</option>
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
                        value="{{ old('description') }}"
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Record Transaction
                </button>
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50" onclick="closeModal('createPettyModal')">
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
</script>
@endsection
