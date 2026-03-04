@extends('layouts.app')

@section('title', 'Donations')
@section('header', 'Donations Management')
@section('subtitle', 'Track and manage all donations to the temple')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-3">
        <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm" onchange="filterByCategory(this.value)">
            <option value="">All Categories</option>
            @foreach($fundCategories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <form method="GET" action="{{ route('donations.index') }}" class="flex items-center gap-0">
            <input type="hidden" name="category" value="{{ request('category') }}" />
            <input type="text" name="q" placeholder="Search donations..." value="{{ request('q') }}" class="px-3 py-2 border border-gray-300 rounded-l-md text-sm" />
            <button type="submit" class="px-3 py-2 bg-saffron text-white text-sm border-l border-orange-400"><i class="fas fa-search"></i></button>
            <a href="{{ route('donations.index') }}" class="px-3 py-2 bg-gray-400 text-white rounded-r-md text-sm hover:bg-gray-500"><i class="fas fa-times"></i></a>
        </form>
    </div>
    <button onclick="openModal('createDonationModal')" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Record Donation</span>
    </button>
</div>

<div class="card-spiritual overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Donor</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Category</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Amount</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Method</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Date</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Status</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $donation)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6">
                            <span class="font-medium text-deep-brown">
                                {{ $donation->is_anonymous ? 'Anonymous' : ($donation->member->user->name ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-3 py-1 bg-saffron/10 text-saffron rounded text-xs font-medium">
                                {{ $donation->fundCategory->name }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right font-semibold text-deep-brown">Rp{{ number_format($donation->amount, 0) }}</td>
                        <td class="py-4 px-6 text-xs uppercase text-gray-600">{{ $donation->donation_method }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600">{{ $donation->donated_at->format('M d, Y') }}</td>
                        <td class="py-4 px-6 text-center">
                            @if($donation->verified_at)
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Verified</span>
                            @else
                                <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Pending</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('donations.show', $donation) }}" class="text-saffron hover:text-rust text-sm font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-gray-600">No donations recorded</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $donations->links() }}
</div>

<!-- create donation modal -->
<div id="createDonationModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white rounded-lg w-11/12 max-w-3xl p-8 relative overflow-auto max-h-[90vh]">
        <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('createDonationModal')">&times;</button>
        <h2 class="text-2xl font-semibold text-deep-brown mb-4">Record New Donation</h2>
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
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50" onclick="closeModal('createDonationModal')">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterByCategory(categoryId) {
    let url = new URL(window.location);
    if (categoryId) {
        url.searchParams.set('category', categoryId);
    } else {
        url.searchParams.delete('category');
    }
    window.location = url.toString();
}

document.getElementById('is_regular').addEventListener('change', function() {
    document.getElementById('frequency-section').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
