@extends('layouts.app')

@section('title', 'Member Details')
@section('header', $member->user->name)
@section('subtitle', 'Member ID: ' . $member->member_id)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Member Info -->
    <div class="lg:col-span-2">
        <div class="card-spiritual p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center space-x-4">
                @if($member->profile_image)
                    <img src="{{ asset('storage/'.$member->profile_image) }}" class="w-20 h-20 rounded-full object-cover" alt="Profile">
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-deep-brown">{{ $member->user->name }}</h2>
                    <p class="text-sm text-gray-600 mb-1">Role: <span class="font-medium text-saffron">{{ ucfirst($member->user->role) }}</span></p>
                    <p class="text-gray-600 mt-1">{{ $member->user->email }}</p>
                </div>
            </div>
                <span class="inline-block px-4 py-2 {{ $member->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} rounded-lg font-semibold text-sm">
                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Phone</p>
                    <p class="text-lg font-medium text-deep-brown">{{ $member->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Birth Date</p>
                    <p class="text-lg font-medium text-deep-brown">{{ $member->birth_date?->format('M d, Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Join Date</p>
                    <p class="text-lg font-medium text-deep-brown">{{ $member->join_date?->format('M d, Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase">Member Since</p>
                    <p class="text-lg font-medium text-deep-brown">{{ $member->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Address</p>
                <p class="text-gray-700">
                    {{ $member->address ?? '-' }}<br>
                    {{ $member->city ?? '' }} {{ $member->province ?? '' }} {{ $member->postal_code ?? '' }}
                </p>
                @if($member->latitude && $member->longitude)
                    <div class="mt-4">
                        <iframe
                            width="100%" height="200" frameborder="0" style="border:0"
                            src="https://www.google.com/maps/embed/v1/view?key={{ config('services.google.maps_key') }}&center={{ $member->latitude }},{{ $member->longitude }}&zoom=15" allowfullscreen>
                        </iframe>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex space-x-4">
                <a href="{{ route('members.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
                <a href="{{ route('members.edit', $member) }}" class="btn-spiritual px-4 py-2 text-white rounded-lg text-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member? This action cannot be undone.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form>
                @if($member->is_active)
                    <form action="{{ route('members.deactivate', $member) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-rust/10 text-rust rounded-lg text-sm font-medium hover:bg-rust/20">
                            <i class="fas fa-ban"></i> Deactivate
                        </button>
                    </form>
                @else
                    <form action="{{ route('members.activate', $member) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">
                            <i class="fas fa-check"></i> Activate
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Donations Section -->
        <div class="card-spiritual p-6 mb-6">
            <h3 class="text-lg font-semibold text-deep-brown mb-4">Recent Donations</h3>
            <div class="space-y-3">
                @forelse($donations as $donation)
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200 last:border-b-0">
                        <div>
                            <p class="font-medium text-deep-brown">{{ $donation->fundCategory->name }}</p>
                            <p class="text-xs text-gray-600">{{ $donation->donated_at->format('M d, Y') }}</p>
                        </div>
                        <p class="font-semibold text-saffron">Rp{{ number_format($donation->amount, 0) }}</p>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">No donations recorded</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar Stats -->
    <div>
        <div class="card-spiritual p-6 mb-6">
            <h3 class="text-lg font-semibold text-deep-brown mb-4">Merit Summary</h3>
            <div class="space-y-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-saffron">{{ $merits->total() }}</p>
                    <p class="text-sm text-gray-600">Total Activities</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-gold">Rp{{ number_format($member->donations()->sum('amount'), 0) }}</p>
                    <p class="text-sm text-gray-600">Total Donations</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-jade">{{ $attendances->total() }}</p>
                    <p class="text-sm text-gray-600">Rituals Attended</p>
                </div>
            </div>
        </div>

        <div class="card-spiritual p-6">
            <h3 class="text-lg font-semibold text-deep-brown mb-4">QR Code</h3>
            <div class="bg-gray-100 p-4 rounded-lg text-center">
                <p class="text-xs text-gray-600 mb-2">Scan for check-in</p>
                <div class="bg-white p-3 rounded">
                    <svg viewBox="0 0 100 100" class="w-full">
                        <!-- Placeholder QR code -->
                        <text x="50" y="50" text-anchor="middle" dominant-baseline="middle" font-size="8">
                            {{ substr($member->qr_code_token, 0, 8) }}
                        </text>
                    </svg>
                </div>
                <p class="text-xs text-gray-600 mt-2">{{ $member->qr_code_token }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    {{ $merits->links() }}
</div>
@endsection
