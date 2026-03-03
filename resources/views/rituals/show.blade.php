@extends('layouts.app')

@section('title', 'Ritual Details')
@section('header', $ritual->title)
@section('subtitle', $ritual->start_time->format('l, F d, Y g:i A'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 card-spiritual p-6">
        <div class="space-y-6">
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Description</p>
                <p class="text-gray-700">{{ $ritual->description }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Type</p>
                    <p class="text-lg font-medium text-deep-brown mt-1 capitalize">{{ $ritual->type }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Location</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $ritual->location ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Capacity</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $ritual->capacity ?? 'Unlimited' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Attendees</p>
                    <p class="text-lg font-medium text-deep-brown mt-1">{{ $ritual->attendances_count ?? 0 }}</p>
                </div>
            </div>

            @if($ritual->special_notes)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-xs font-semibold text-yellow-900 uppercase mb-2">Special Notes</p>
                    <p class="text-yellow-800">{{ $ritual->special_notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <div>
        <div class="card-spiritual p-6 mb-6">
            <h3 class="text-lg font-semibold text-deep-brown mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('rituals.edit', $ritual) }}" class="block w-full px-4 py-2 btn-spiritual text-white rounded-lg text-center text-sm font-medium">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('rituals.show', $ritual) }}?register" class="block w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg text-center text-sm font-medium hover:bg-green-200">
                    <i class="fas fa-user-plus"></i> Register Member
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card-spiritual p-6">
    <h3 class="text-lg font-semibold text-deep-brown mb-4">Attendees</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Member</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Check-in</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Check-out</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $attendance->member->user->name }}</td>
                        <td class="py-3 px-4">{{ $attendance->checked_in_at?->format('g:i A') ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $attendance->checked_out_at?->format('g:i A') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 px-4 text-center text-gray-600">No attendees yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
