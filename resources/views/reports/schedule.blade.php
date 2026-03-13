@extends('layouts.app')

@section('title', 'Schedule Report')
@section('header', 'Schedule & Rituals Report')
@section('subtitle', 'Overview of all scheduled activities and events')

@section('content')
<!-- Export Buttons -->
<div class="mb-6 flex gap-3 flex-wrap">
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
        <i class="fas fa-print"></i> Print
    </button>
    <a href="{{ route('reports.schedule.export', ['format' => 'pdf', 'type' => request('type'), 'status' => request('status'), 'start_date' => request('start_date')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>
    <a href="{{ route('reports.schedule.export', ['format' => 'excel', 'type' => request('type'), 'status' => request('status'), 'start_date' => request('start_date')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
</div>

<div class="mb-6 card-spiritual p-6">
    <form action="{{ route('reports.schedule') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Type</label>
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Types</option>
                <option value="puja" {{ request('type') == 'puja' ? 'selected' : '' }}>Puja</option>
                <option value="meditation" {{ request('type') == 'meditation' ? 'selected' : '' }}>Meditation</option>
                <option value="teaching" {{ request('type') == 'teaching' ? 'selected' : '' }}>Teaching</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Events</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-deep-brown mb-2">Start Date</label>
            <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('start_date') }}">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium w-full">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="card-spiritual p-6 border-blue-500 border-t-4">
        <p class="text-gray-600 text-sm">Total Events</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalRituals }}</p>
        <p class="text-xs text-gray-600 mt-2">Scheduled activities</p>
    </div>

    <div class="card-spiritual p-6 border-saffron border-t-4">
        <p class="text-gray-600 text-sm">Upcoming Events</p>
        <p class="text-3xl font-bold text-saffron mt-2">{{ $upcomingCount }}</p>
        <p class="text-xs text-gray-600 mt-2">Next 30 days</p>
    </div>

    <div class="card-spiritual p-6 border-green-500 border-t-4">
        <p class="text-gray-600 text-sm">Recurring Events</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $recurringCount }}</p>
        <p class="text-xs text-gray-600 mt-2">Regular schedule</p>
    </div>

    <div class="card-spiritual p-6 border-jade border-t-4">
        <p class="text-gray-600 text-sm">Total Attendance</p>
        <p class="text-3xl font-bold text-jade mt-2">{{ $totalAttendance }}</p>
        <p class="text-xs text-gray-600 mt-2">Registrations</p>
    </div>

    <div class="card-spiritual p-6 border-red-500 border-t-4">
        <p class="text-gray-600 text-sm">Avg Capacity</p>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $avgCapacity }}</p>
        <p class="text-xs text-gray-600 mt-2">Per event</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Pie Chart: Events by Type -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Events by Type</h3>
        <canvas id="eventTypeChart" class="max-w-full"></canvas>
    </div>

    <!-- Bar Chart: Attendance Distribution -->
    <div class="card-spiritual p-6">
        <h3 class="text-lg font-semibold text-deep-brown mb-4">Top Events by Attendance</h3>
        <canvas id="attendanceChart" class="max-w-full"></canvas>
    </div>
</div>

<!-- Upcoming Events -->
<div class="card-spiritual p-6 mb-8">
    <h3 class="text-lg font-semibold text-deep-brown mb-4">Next 5 Upcoming Events</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @php
            $nextEvents = $rituals->filter(fn($r) => $r->isUpcoming())->sortBy('start_time')->take(5);
        @endphp
        @forelse($nextEvents as $ritual)
            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="mb-2">
                    <p class="text-sm font-semibold text-deep-brown truncate">{{ $ritual->title }}</p>
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded capitalize">
                        {{ $ritual->type }}
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">
                    <i class="fas fa-calendar"></i> {{ $ritual->start_time->format('M d, Y') }}
                </p>
                <p class="text-xs text-gray-600">
                    <i class="fas fa-location-dot"></i> {{ substr($ritual->location, 0, 15) }}
                </p>
                <p class="text-xs text-gray-600 border-t border-blue-100 pt-1 mt-1">
                    <i class="fas fa-users"></i> {{ $ritual->attendances()->count() }}/{{ $ritual->capacity ?? '∞' }}
                </p>
            </div>
        @empty
            <p class="text-gray-600 text-sm col-span-full text-center py-4">No upcoming events</p>
        @endforelse
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Event Type Chart
    const eventTypeCtx = document.getElementById('eventTypeChart').getContext('2d');
    const eventTypeChart = new Chart(eventTypeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($eventsByType->keys()->map(fn($k) => ucfirst($k) ?: 'Uncategorized')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($eventsByType->values()->map(fn($items) => count($items))->toArray()) !!},
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
                }
            }
        }
    });

    // Attendance Chart
    @php
        $topEventsByAttendance = collect($rituals)
            ->sortByDesc(fn($r) => $r->attendances()->count())
            ->take(8)
            ->map(fn($r) => [
                'title' => substr($r->title, 0, 12),
                'attendance' => $r->attendances()->count()
            ]);
    @endphp
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(attendanceCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topEventsByAttendance->pluck('title')->toArray()) !!},
            datasets: [{
                label: 'Attendees',
                data: {!! json_encode($topEventsByAttendance->pluck('attendance')->toArray()) !!},
                backgroundColor: '#2A9D8F',
                borderColor: '#1d7a6e',
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
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
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
        <h3 class="text-lg font-semibold text-deep-brown">All Schedule Events</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Event Title</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Type</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Start Time</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Location</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Attendance</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Capacity</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Type</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rituals as $ritual)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-gray-700 font-medium">{{ $ritual->title }}</td>
                        <td class="py-4 px-6 text-gray-600 capitalize">{{ $ritual->type ?? '-' }}</td>
                        <td class="py-4 px-6 text-gray-600 text-xs">
                            {{ $ritual->start_time->format('M d, Y g:i A') }}
                        </td>
                        <td class="py-4 px-6 text-gray-600">{{ $ritual->location }}</td>
                        <td class="py-4 px-6 text-center font-medium">
                            {{ $ritual->attendances()->count() }}
                        </td>
                        <td class="py-4 px-6 text-center text-gray-600">
                            {{ $ritual->capacity ?? 'Unlimited' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($ritual->is_recurring)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded uppercase">
                                    Recurring
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded uppercase">
                                    One-time
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($ritual->isUpcoming())
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded uppercase">
                                    Upcoming
                                </span>
                            @elseif($ritual->isPast())
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded uppercase">
                                    Past
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 px-6 text-center text-gray-600">No events found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
