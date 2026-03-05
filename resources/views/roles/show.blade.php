@extends('layouts.app')

@section('title', 'Role Details')
@section('header', 'Role Information')
@section('subtitle', 'View details for the role')

@section('content')
<div class="max-w-lg space-y-4">
    <div>
        <h3 class="text-sm font-semibold text-gray-700">Identifier</h3>
        <p class="text-deep-brown">{{ $role->name }}</p>
    </div>
    <div>
        <h3 class="text-sm font-semibold text-gray-700">Label</h3>
        <p>{{ $role->label ?? '-' }}</p>
    </div>
    <div>
        <h3 class="text-sm font-semibold text-gray-700">Description</h3>
        <p>{{ $role->description ?? '-' }}</p>
    </div>
    <div class="mt-6">
        <a href="{{ route('roles.edit', $role) }}" class="px-6 py-2 bg-saffron text-white rounded-lg hover:bg-gold transition btn-animated">Edit</a>
        <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.');" class="inline ml-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition btn-animated">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
        </form>
        <a href="{{ route('roles.index') }}" class="ml-4 text-gray-600 hover:underline">Back to list</a>
    </div>
</div>
@endsection
