@extends('layouts.app')

@section('title', 'Financial Report')
@section('header', 'Financial Summary Report')
@section('subtitle', 'Complete financial overview for the selected period')

@section('content')
<div class="mb-6 card-spiritual p-6">
    <form action="{{ route('reports.financial-summary') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Start Date</label>
            <input type="date" name="start_date" class="px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">End Date</label>
            <input type="date" name="end_date" class="px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="card-spiritual p-6 border-green-500 border-t-4">
        <p class="text-gray-600 text-sm">Total Income</p>
        <p class="text-3xl font-bold text-green-600 mt-2">Rp{{ number_format($totalIncome, 0) }}</p>
        <p class="text-xs text-gray-600 mt-2">{{ $donations->count() }} donations</p>
    </div>
    <div class="card-spiritual p-6 border-red-500 border-t-4">
        <p class="text-gray-600 text-sm">Total Expenses</p>
        <p class="text-3xl font-bold text-red-600 mt-2">Rp{{ number_format($totalExpense, 0) }}</p>
        <p class="text-xs text-gray-600 mt-2">{{ $expenses->count() }} transactions</p>
    </div>
    <div class="card-spiritual p-6 {{ $netBalance >= 0 ? 'border-green-500' : 'border-red-500' }} border-t-4">
        <p class="text-gray-600 text-sm">Net Balance</p>
        <p class="text-3xl font-bold {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
            Rp{{ number_format($netBalance, 0) }}
        </p>
        <p class="text-xs text-gray-600 mt-2">{{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Income by Category -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Income by Category</h3>
        <div class="space-y-3">
            @forelse($byCategory as $category => $data)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $category }}</span>
                        <span class="text-sm font-semibold text-saffron">Rp{{ number_format($data['amount'], 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-saffron h-2 rounded-full" style="width: {{ ($data['amount'] / $totalIncome) * 100 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">{{ $data['count'] }} donations</p>
                </div>
            @empty
                <p class="text-gray-600 text-sm">No income data</p>
            @endforelse
        </div>
    </div>

    <!-- Expense Summary -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Top Expenses</h3>
        <div class="space-y-2">
            @php
                $expensesByCategory = $expenses->groupBy('category')->map(function($items) {
                    return $items->sum('amount');
                })->sortDesc();
            @endphp
            @forelse($expensesByCategory as $category => $amount)
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-sm text-gray-700 capitalize">{{ $category }}</span>
                    <span class="text-sm font-semibold text-rust">Rp{{ number_format($amount, 0) }}</span>
                </div>
            @empty
                <p class="text-gray-600 text-sm">No expense data</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
