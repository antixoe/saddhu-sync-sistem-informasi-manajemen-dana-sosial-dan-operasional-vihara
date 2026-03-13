@extends('layouts.app')

@section('title', 'Donations Report')
@section('header', 'Donations Report')
@section('subtitle', 'Detailed analysis of all donation transactions')

@section('content')
<!-- Export Buttons -->
<div class="mb-6 flex gap-3 flex-wrap">
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
        <i class="fas fa-print"></i> Print
    </button>
    <a href="{{ route('reports.donations.export', ['format' => 'pdf', 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'fund_category_id' => request('fund_category_id'), 'donation_method' => request('donation_method')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>
    <a href="{{ route('reports.donations.export', ['format' => 'excel', 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'fund_category_id' => request('fund_category_id'), 'donation_method' => request('donation_method')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
</div>

<div class="mb-6 card-spiritual p-6">
    <form action="{{ route('reports.donations') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Start Date</label>
            <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('start_date') }}">
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">End Date</label>
            <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('end_date') }}">
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Fund Category</label>
            <select name="fund_category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Categories</option>
                @php
                    $categories = \App\Models\FundCategory::all();
                @endphp
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('fund_category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Method</label>
            <select name="donation_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Methods</option>
                <option value="qris" {{ request('donation_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="bank_transfer" {{ request('donation_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="cash" {{ request('donation_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="other" {{ request('donation_method') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium w-full">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card-spiritual p-6 border-green-500 border-t-4">
        <p class="text-gray-600 text-sm">Total Amount</p>
        <p class="text-3xl font-bold text-green-600 mt-2">Rp{{ number_format($totalAmount, 0) }}</p>
        <p class="text-xs text-gray-600 mt-2">{{ $donations->count() }} donations</p>
    </div>

    <div class="card-spiritual p-6 border-saffron border-t-4">
        <p class="text-gray-600 text-sm">Average Donation</p>
        <p class="text-3xl font-bold text-saffron mt-2">
            Rp{{ number_format($donations->count() > 0 ? $totalAmount / $donations->count() : 0, 0) }}
        </p>
        <p class="text-xs text-gray-600 mt-2">Per transaction</p>
    </div>

    <div class="card-spiritual p-6 border-jade border-t-4">
        <p class="text-gray-600 text-sm">Largest Donation</p>
        <p class="text-3xl font-bold text-jade mt-2">
            Rp{{ number_format($donations->max('amount') ?? 0, 0) }}
        </p>
        <p class="text-xs text-gray-600 mt-2">Single transaction</p>
    </div>

    <div class="card-spiritual p-6 border-blue-500 border-t-4">
        <p class="text-gray-600 text-sm">Regular Donors</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $donations->where('is_regular', true)->count() }}</p>
        <p class="text-xs text-gray-600 mt-2">Active subscriptions</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Pie Chart: Donations by Category -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Donations by Category</h3>
        <canvas id="categoryChart" class="max-w-full"></canvas>
    </div>

    <!-- Bar Chart: Donations by Method -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Donations by Method</h3>
        <canvas id="methodChart" class="max-w-full"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($groupedByCategory->keys()->map(fn($k) => $k ?: 'Uncategorized')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($groupedByCategory->values()->map(fn($items) => $items->sum('amount'))->toArray()) !!},
                backgroundColor: ['#F4A261', '#2A9D8F', '#E76F51', '#264653', '#E9C46A', '#D62828'],
                borderColor: '#ffffff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp' + context.parsed.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Method Chart
    const methodCtx = document.getElementById('methodChart').getContext('2d');
    const methodChart = new Chart(methodCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($groupedByMethod->keys()->map(fn($k) => str_replace('_', ' ', ucfirst($k)))->toArray()) !!},
            datasets: [{
                label: 'Amount (Rp)',
                data: {!! json_encode($groupedByMethod->values()->map(fn($items) => $items->sum('amount'))->toArray()) !!},
                backgroundColor: '#2A9D8F',
                borderColor: '#1d7a6e',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

<!-- Detailed Table -->
<div class="card-spiritual overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-deep-brown">All Donations</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Date</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Donor</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Category</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Method</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Amount</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Type</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $donation)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-gray-600 text-xs">
                            {{ $donation->donated_at->format('M d, Y') }}
                        </td>
                        <td class="py-4 px-6 text-gray-700">
                            @if($donation->is_anonymous)
                                <span class="text-gray-500 italic">Anonymous</span>
                            @else
                                {{ $donation->member->name ?? 'Unknown' }}
                            @endif
                        </td>
                        <td class="py-4 px-6 text-gray-700">{{ $donation->fundCategory->name ?? '-' }}</td>
                        <td class="py-4 px-6 text-gray-700 capitalize">{{ str_replace('_', ' ', $donation->donation_method) }}</td>
                        <td class="py-4 px-6 text-right font-semibold text-green-600">
                            Rp{{ number_format($donation->amount, 0) }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($donation->is_regular)
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded uppercase">
                                    Regular
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded uppercase">
                                    One-time
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($donation->verified_at)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded uppercase">
                                    Verified
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded uppercase">
                                    Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-gray-600">No donations found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
