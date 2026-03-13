@extends('layouts.app')

@section('title', 'Inventory Stock Report')
@section('header', 'Inventory Stock Report')
@section('subtitle', 'Complete inventory status and stock levels')

@section('content')
<!-- Export Buttons -->
<div class="mb-6 flex gap-3 flex-wrap">
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
        <i class="fas fa-print"></i> Print
    </button>
    <a href="{{ route('reports.inventory.export', ['format' => 'pdf', 'category' => request('category'), 'status' => request('status')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>
    <a href="{{ route('reports.inventory.export', ['format' => 'excel', 'category' => request('category'), 'status' => request('status')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
</div>

<div class="mb-6 card-spiritual p-6">
    <form action="{{ route('reports.inventory') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Category</label>
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Categories</option>
                @php
                    $categories = \App\Models\InventoryItem::distinct('category')->pluck('category');
                @endphp
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Status</label>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Items</option>
                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
            </select>
        </div>
        <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="card-spiritual p-6 border-blue-500 border-t-4">
        <p class="text-gray-600 text-sm">Total Items</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $items->count() }}</p>
        <p class="text-xs text-gray-600 mt-2">Tracked inventory</p>
    </div>

    <div class="card-spiritual p-6 border-green-500 border-t-4">
        <p class="text-gray-600 text-sm">Total Stock Value</p>
        <p class="text-3xl font-bold text-green-600 mt-2">Rp{{ number_format($totalValue, 0) }}</p>
        <p class="text-xs text-gray-600 mt-2">All items combined</p>
    </div>

    <div class="card-spiritual p-6 border-red-500 border-t-4">
        <p class="text-gray-600 text-sm">Low Stock Items</p>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $lowStockCount }}</p>
        <p class="text-xs text-gray-600 mt-2">Needs reordering</p>
    </div>

    <div class="card-spiritual p-6 border-saffron border-t-4">
        <p class="text-gray-600 text-sm">Categories</p>
        <p class="text-3xl font-bold text-saffron mt-2">{{ $categoryCount }}</p>
        <p class="text-xs text-gray-600 mt-2">Different types</p>
    </div>

    <div class="card-spiritual p-6 border-jade border-t-4">
        <p class="text-gray-600 text-sm">Avg Unit Price</p>
        <p class="text-3xl font-bold text-jade mt-2">Rp{{ number_format($avgPrice, 0) }}</p>
        <p class="text-xs text-gray-600 mt-2">Per item</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Pie Chart: Stock Value by Category -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Stock Value by Category</h3>
        <canvas id="categoryValueChart" class="max-w-full"></canvas>
    </div>

    <!-- Bar Chart: Top Items by Value -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Top 8 Items by Value</h3>
        <canvas id="topItemsChart" class="max-w-full"></canvas>
    </div>
</div>

<!-- Low Stock Alert -->
<div class="card-spiritual p-6 mb-8">
    <h3 class="text-lg font-semibold text-deep-brown mb-4">Low Stock Items Alert</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @php
            $lowStockItems = $items->filter(fn($item) => $item->isLowStock());
        @endphp
        @forelse($lowStockItems as $item)
            <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-deep-brown">{{ $item->name }}</p>
                        <p class="text-xs text-gray-600">{{ $item->category }}</p>
                    </div>
                    <span class="inline-block px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">
                        {{ $item->quantity }} {{ $item->unit }}
                    </span>
                </div>
                <p class="text-xs text-red-600 mt-2">
                    <i class="fas fa-exclamation-triangle"></i> Reorder level: {{ $item->reorder_level }} {{ $item->unit }}
                </p>
            </div>
        @empty
            <p class="text-gray-600 text-sm col-span-full text-center py-4">All items have sufficient stock</p>
        @endforelse
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Category Value Chart
    @php
        $categoryValues = [];
        foreach($itemsByCategory as $category => $categoryItems) {
            $categoryValues[$category] = $categoryItems->sum(function($item) {
                return ($item->purchase_price ?? 0) * $item->quantity;
            });
        }
    @endphp
    const categoryValueCtx = document.getElementById('categoryValueChart').getContext('2d');
    const categoryValueChart = new Chart(categoryValueCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($categoryValues)) !!},
            datasets: [{
                data: {!! json_encode(array_values($categoryValues)) !!},
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

    // Top Items Chart
    @php
        $topItems = $items->map(function($item) {
            return [
                'name' => substr($item->name, 0, 12),
                'value' => ($item->purchase_price ?? 0) * $item->quantity
            ];
        })->sortByDesc('value')->take(8);
    @endphp
    const topItemsCtx = document.getElementById('topItemsChart').getContext('2d');
    const topItemsChart = new Chart(topItemsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topItems->pluck('name')->toArray()) !!},
            datasets: [{
                label: 'Value (Rp)',
                data: {!! json_encode($topItems->pluck('value')->toArray()) !!},
                backgroundColor: '#F4A261',
                borderColor: '#d88a3d',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp' + context.parsed.x.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + (value / 1000000).toFixed(1) + 'M';
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
        <h3 class="text-lg font-semibold text-deep-brown">All Items</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Item Name</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Category</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Quantity</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Unit Price</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Total Value</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Reorder Level</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-gray-700 font-medium">{{ $item->name }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ $item->category }}</td>
                        <td class="py-4 px-6 text-center font-medium">
                            {{ $item->quantity }} <span class="text-gray-500 text-xs">{{ $item->unit }}</span>
                        </td>
                        <td class="py-4 px-6 text-right text-gray-700">
                            Rp{{ number_format($item->purchase_price ?? 0, 0) }}
                        </td>
                        <td class="py-4 px-6 text-right font-semibold text-green-600">
                            Rp{{ number_format(($item->purchase_price ?? 0) * $item->quantity, 0) }}
                        </td>
                        <td class="py-4 px-6 text-center text-gray-600">
                            {{ $item->reorder_level ?? '-' }} <span class="text-gray-500 text-xs">{{ $item->unit }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($item->isLowStock())
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded uppercase">
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded uppercase">
                                    In Stock
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-gray-600">No inventory items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
