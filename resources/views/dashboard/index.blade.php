@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subtitle', 'Overview of temple operations and finances')

@section('content')
<!-- interactive stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Members -->
    <div class="card-spiritual p-6 cursor-pointer hover:bg-gray-50" onclick="openModal('membersModal')">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Members</p>
                <p class="text-3xl font-bold text-deep-brown mt-2">{{ $totalMembers }}</p>
            </div>
            <i class="fas fa-users text-4xl text-saffron opacity-20"></i>
        </div>
    </div>

    <!-- Total Donations -->
    <div class="card-spiritual p-6 cursor-pointer hover:bg-gray-50" onclick="openModal('donationsModal')">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Donations</p>
                <p class="text-2xl font-bold text-deep-brown mt-2">Rp{{ number_format($totalDonations, 0) }}</p>
            </div>
            <i class="fas fa-hands-praying text-4xl text-saffron opacity-20"></i>
        </div>
    </div>

    <!-- This Month -->
    <div class="card-spiritual p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">This Month</p>
                <p class="text-2xl font-bold text-deep-brown mt-2">Rp{{ number_format($totalDonationsMonth, 0) }}</p>
            </div>
            <i class="fas fa-calendar text-4xl text-saffron opacity-20"></i>
        </div>
    </div>

    <!-- Fund Categories -->
    <div class="card-spiritual p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Fund Categories</p>
                <p class="text-3xl font-bold text-deep-brown mt-2">{{ $fundCategories->count() }}</p>
            </div>
            <i class="fas fa-sitemap text-4xl text-saffron opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Upcoming Rituals -->
    <div class="lg:col-span-2 card-spiritual p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-deep-brown flex items-center space-x-2">
                <i class="fas fa-bell text-saffron"></i>
                <span>Upcoming Rituals</span>
            </h3>
            <a href="{{ route('rituals.index') }}" class="text-sm text-saffron hover:text-rust">View All</a>
        </div>
        <div class="space-y-3">
            @forelse($upcomingRituals as $ritual)
                <div class="flex items-start space-x-4 pb-3 border-b border-gray-200 last:border-b-0">
                    <div class="bg-saffron/10 p-3 rounded-lg">
                        <i class="fas fa-{{ $ritual->type === 'prayer' ? 'pray' : 'calendar' }} text-saffron"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-deep-brown">{{ $ritual->title }}</p>
                        <p class="text-xs text-gray-600">{{ $ritual->start_time->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 text-sm">No upcoming rituals</p>
            @endforelse
        </div>
    </div>

    <!-- Low Stock Items -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown flex items-center space-x-2 mb-4">
            <i class="fas fa-exclamation-triangle text-rust"></i>
            <span>Low Stock</span>
        </h3>
        <div class="space-y-3">
            @forelse($lowStockItems as $item)
                <div class="text-sm">
                    <div class="flex justify-between mb-1">
                        <span class="font-medium text-deep-brown">{{ $item->name }}</span>
                        <span class="text-rust font-semibold">{{ $item->quantity }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-rust h-2 rounded-full" style="width: {{ min(($item->quantity / $item->reorder_level) * 100, 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 text-sm">All items in stock</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Donations -->
<div class="card-spiritual p-6 mb-8">
    <!-- modals section -->
    <div id="membersModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('membersModal')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Members</h2>
            <p class="mt-2">Active members: {{ $totalMembers }}</p>
            <a href="{{ route('members.index') }}" class="text-saffron underline mt-4 inline-block">View all members</a>
        </div>
    </div>

    <div id="donationsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('donationsModal')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Donations</h2>
            <p class="mt-2">Total amount: Rp{{ number_format($totalDonations,0) }}</p>
            <a href="{{ route('donations.index') }}" class="text-saffron underline mt-4 inline-block">View donation history</a>
        </div>
    </div>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-deep-brown flex items-center space-x-2">
            <i class="fas fa-hands-praying text-saffron"></i>
            <span>Recent Donations</span>
        </h3>
        <a href="{{ route('donations.index') }}" class="text-sm text-saffron hover:text-rust">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Member</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Category</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Amount</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Method</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentDonations as $donation)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $donation->is_anonymous ? 'Anonymous' : ($donation->member->user->name ?? 'N/A') }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2 py-1 bg-saffron/10 text-saffron rounded text-xs font-medium">
                                {{ $donation->fundCategory->name }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-deep-brown">Rp{{ number_format($donation->amount, 0) }}</td>
                        <td class="py-3 px-4 text-xs uppercase text-gray-600">{{ $donation->donation_method }}</td>
                        <td class="py-3 px-4 text-xs text-gray-600">{{ $donation->donated_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-gray-600">No donations yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



<script>
    // modal helpers
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    // update time display dynamically
    function updateTime() {
        const t = document.getElementById('time');
        if (t) {
            t.textContent = new Date().toLocaleTimeString();
        }
    }
    setInterval(updateTime, 1000);
    document.addEventListener('click', function(e) {
        // close modal when clicking outside content
        if (e.target.classList.contains('bg-opacity-50')) {
            e.target.classList.add('hidden');
        }
    });
</script>
@endsection
