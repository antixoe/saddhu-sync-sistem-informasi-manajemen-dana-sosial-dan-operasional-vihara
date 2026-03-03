@extends('layouts.app')

@section('title', 'Activity Log')
@section('header', 'Activity Log')
@section('subtitle', 'Audit trail of all system changes and activities')

@section('content')
<div class="mb-6 card-spiritual p-6">
    <form action="{{ route('reports.activity-log') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Start Date</label>
            <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('start_date') }}">
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">End Date</label>
            <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('end_date') }}">
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Action</label>
            <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Actions</option>
                <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                <option value="verified" {{ request('action') == 'verified' ? 'selected' : '' }}>Verified</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium w-full">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<div class="card-spiritual overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Time</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">User</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Action</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Resource</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-gray-600 text-xs">{{ $log->created_at->format('M d, Y g:i A') }}</td>
                        <td class="py-4 px-6 text-gray-700">{{ $log->user->name ?? 'System' }}</td>
                        <td class="py-4 px-6">
                            @php
                                $actionColors = [
                                    'created' => 'green',
                                    'updated' => 'blue',
                                    'deleted' => 'red',
                                    'verified' => 'yellow',
                                ];
                                $color = $actionColors[$log->action] ?? 'gray';
                            @endphp
                            <span class="inline-block px-3 py-1 bg-{{ $color }}-100 text-{{ $color }}-700 text-xs font-semibold rounded uppercase">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-gray-700">{{ $log->model_type }} #{{ $log->model_id }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-6 text-center text-gray-600">No activity logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $logs->links() }}
</div>
@endsection
