@extends('layouts.app')

@section('title', 'Rituals & Events')
@section('header', 'Rituals & Events')
@section('subtitle', 'Schedule and manage temple rituals and events')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-gray-600">Total Events: <span class="font-bold text-deep-brown">{{ $rituals->total() }}</span></p>
    </div>
    <div class="flex items-center space-x-4">
        <form method="GET" action="{{ route('rituals.index') }}" class="flex items-center">
            <input type="text" name="q" placeholder="Search rituals..." value="{{ request('q') }}" class="px-3 py-2 border border-gray-300 rounded-l-md text-sm" />
            <button type="submit" class="px-3 py-2 bg-saffron text-white rounded-r-md text-sm"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <button onclick="openModal('createRitualModal')" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Add Ritual/Event</span>
    </button>
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
    {{ $rituals->links() }}
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
@endsection
