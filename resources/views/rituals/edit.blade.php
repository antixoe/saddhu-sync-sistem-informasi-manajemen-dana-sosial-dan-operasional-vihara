@extends('layouts.app')

@section('title', 'Edit Ritual')
@section('header', 'Edit Ritual: ' . $ritual->title)
@section('subtitle', 'Update ritual/event information')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
        <form action="{{ route('rituals.update', $ritual) }}" method="POST" class="space-y-6">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-deep-brown mb-2">Title</label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('title', $ritual->title) }}">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-deep-brown mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('description', $ritual->description) }}</textarea>
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-deep-brown mb-2">Type</label>
                    <select name="type" id="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="prayer" {{ $ritual->type == 'prayer' ? 'selected' : '' }}>Prayer/Meditation</option>
                        <option value="ceremony" {{ $ritual->type == 'ceremony' ? 'selected' : '' }}>Ceremony</option>
                        <option value="class" {{ $ritual->type == 'class' ? 'selected' : '' }}>Dhamma Class</option>
                        <option value="special_event" {{ $ritual->type == 'special_event' ? 'selected' : '' }}>Special Event</option>
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-semibold text-deep-brown mb-2">Location</label>
                    <input type="text" name="location" id="location"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('location', $ritual->location) }}">
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-semibold text-deep-brown mb-2">Start Date & Time</label>
                    <input type="datetime-local" name="start_time" id="start_time" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('start_time', $ritual->start_time->format('Y-m-d\TH:i')) }}">
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-semibold text-deep-brown mb-2">End Date & Time</label>
                    <input type="datetime-local" name="end_time" id="end_time"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('end_time', $ritual->end_time?->format('Y-m-d\TH:i')) }}">
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-semibold text-deep-brown mb-2">Capacity</label>
                    <input type="number" name="capacity" id="capacity" min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('capacity', $ritual->capacity) }}">
                </div>

                <!-- Special Notes -->
                <div class="md:col-span-2">
                    <label for="special_notes" class="block text-sm font-semibold text-deep-brown mb-2">Special Notes</label>
                    <textarea name="special_notes" id="special_notes" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('special_notes', $ritual->special_notes) }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
                <a href="{{ route('rituals.show', $ritual) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
