@extends('layouts.app')

@section('title', 'Add Ritual')
@section('header', 'Create New Ritual/Event')
@section('subtitle', 'Schedule a new temple ritual or event')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
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
                <a href="{{ route('rituals.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
