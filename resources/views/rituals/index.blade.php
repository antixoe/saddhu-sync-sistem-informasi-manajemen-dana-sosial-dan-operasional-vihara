@extends('layouts.app')

@section('title', 'Rituals & Events')
@section('header', 'Rituals & Events')
@section('subtitle', 'Schedule and manage temple rituals and events')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-gray-600">Total Events: <span class="font-bold text-deep-brown">{{ $rituals->total() }}</span></p>
    </div>
    <button onclick="openModal('createRitualModal')" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Add Ritual/Event</span>
    </button>
</div>

{{-- Filter and Sort Controls --}}
<div class="card-spiritual p-4 mb-6">
    <form method="GET" action="{{ route('rituals.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <!-- Search -->
            <div>
                <label for="q" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                <input type="text" name="q" id="q" placeholder="Search rituals..." value="{{ $filters['q'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20" />
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ ($filters['type'] ?? '') == $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label for="sort" class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                    <option value="latest" {{ ($filters['sort'] ?? 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ ($filters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="earliest_date" {{ ($filters['sort'] ?? '') == 'earliest_date' ? 'selected' : '' }}>Earliest Date</option>
                    <option value="latest_date" {{ ($filters['sort'] ?? '') == 'latest_date' ? 'selected' : '' }}>Latest Date</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-saffron text-white rounded-lg text-sm hover:bg-orange-600 transition">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('rituals.index') }}" class="flex-1 px-4 py-2 bg-gray-400 text-white rounded-lg text-sm hover:bg-gray-500 transition text-center">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </div>
    </form>
</div>

<div class="space-y-4">
    @forelse($rituals as $ritual)
        <div class="card-spiritual p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4 flex-1">
                    <div class="bg-saffron/10 p-4 rounded-lg">
                        <i class="fas fa-{{ $ritual->type === 'prayer' ? 'pray' : ($ritual->type === 'class' ? 'book' : 'calendar') }} text-2xl text-saffron"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-deep-brown">{{ $ritual->title }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $ritual->description }}</p>
                        <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-600">
                            <span><i class="fas fa-clock text-saffron"></i> {{ $ritual->start_time->format('M d, Y g:i A') }}</span>
                            @if($ritual->location)
                                <span><i class="fas fa-map-marker-alt text-saffron"></i> {{ $ritual->location }}</span>
                            @endif
                            @if($ritual->capacity)
                                <span><i class="fas fa-users text-saffron"></i> Capacity: {{ $ritual->capacity }}</span>
                            @endif
                            <span>
                                <i class="fas fa-check-circle text-green-600"></i> 
                                {{ $ritual->attendances_count ?? 0 }} attendees
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('rituals.show', $ritual) }}" class="text-saffron hover:text-rust font-medium text-sm">View Details</a>
                    <a href="{{ route('rituals.edit', $ritual) }}" class="text-saffron hover:text-rust font-medium text-sm">Edit</a>
                    <form action="{{ route('rituals.destroy', $ritual) }}" method="POST" onsubmit="return confirm('Delete this ritual/event?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="card-spiritual p-12 text-center">
            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-600">No rituals or events scheduled</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{-- Custom Pagination Buttons --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Showing {{ $rituals->firstItem() ?? 0 }} to {{ $rituals->lastItem() ?? 0 }} of {{ $rituals->total() }} events
        </p>
        <div class="flex items-center gap-2">
            {{-- Previous Button --}}
            @if($rituals->onFirstPage())
                <button disabled class="px-3 py-2 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
            @else
                <a href="{{ $rituals->previousPageUrl() }}" class="px-3 py-2 border border-saffron text-saffron hover:bg-saffron hover:text-white rounded-lg transition">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            @endif

            {{-- Page Numbers --}}
            <div class="flex gap-1">
                @for ($i = 1; $i <= $rituals->lastPage(); $i++)
                    @if ($i == $rituals->currentPage())
                        <button disabled class="px-3 py-2 bg-saffron text-white rounded-lg font-semibold">{{ $i }}</button>
                    @else
                        <a href="{{ $rituals->url($i) }}" class="px-3 py-2 border border-gray-300 hover:border-saffron hover:text-saffron rounded-lg transition">{{ $i }}</a>
                    @endif
                @endfor
            </div>

            {{-- Next Button --}}
            @if($rituals->hasMorePages())
                <a href="{{ $rituals->nextPageUrl() }}" class="px-3 py-2 border border-saffron text-saffron hover:bg-saffron hover:text-white rounded-lg transition">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <button disabled class="px-3 py-2 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            @endif
        </div>
    </div>
</div>

<!-- create ritual modal -->
<div id="createRitualModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white rounded-lg w-11/12 max-w-3xl p-8 relative overflow-auto max-h-[90vh]">
        <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('createRitualModal')">&times;</button>
        <h2 class="text-2xl font-semibold text-deep-brown mb-4">Create New Ritual/Event</h2>
        <form action="{{ route('rituals.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-heading text-saffron"></i> Title *
                    </label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('title') }}">
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-align-left text-saffron"></i> Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('description') }}</textarea>
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-list text-saffron"></i> Type *
                    </label>
                    <select name="type" id="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="prayer" {{ old('type') == 'prayer' ? 'selected' : '' }}>Prayer/Meditation</option>
                        <option value="ceremony" {{ old('type') == 'ceremony' ? 'selected' : '' }}>Ceremony</option>
                        <option value="class" {{ old('type') == 'class' ? 'selected' : '' }}>Dhamma Class</option>
                        <option value="special_event" {{ old('type') == 'special_event' ? 'selected' : '' }}>Special Event</option>
                    </select>
                    @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map-marker-alt text-saffron"></i> Location
                    </label>
                    <input type="text" name="location" id="location"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('location') }}">
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-clock text-saffron"></i> Start Date & Time *
                    </label>
                    <input type="datetime-local" name="start_time" id="start_time" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('start_time') }}">
                    @error('start_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-flag-checkered text-saffron"></i> End Date & Time
                    </label>
                    <input type="datetime-local" name="end_time" id="end_time"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('end_time') }}">
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-users text-saffron"></i> Capacity
                    </label>
                    <input type="number" name="capacity" id="capacity" min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('capacity') }}">
                </div>

                <!-- Requires Registration -->
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="requires_registration" value="1" {{ old('requires_registration') ? 'checked' : '' }}>
                        <span class="text-sm font-semibold text-deep-brown">
                            <i class="fas fa-user-check text-saffron"></i> Requires Registration
                        </span>
                    </label>
                </div>

                <!-- Special Notes -->
                <div class="md:col-span-2">
                    <label for="special_notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Special Notes
                    </label>
                    <textarea name="special_notes" id="special_notes" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('special_notes') }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Create Ritual
                </button>
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50" onclick="closeModal('createRitualModal')">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function filterByType(typeValue) {
    let url = new URL(window.location);
    if (typeValue) {
        url.searchParams.set('type', typeValue);
    } else {
        url.searchParams.delete('type');
    }
    window.location = url.toString();
}
</script>
@endsection
