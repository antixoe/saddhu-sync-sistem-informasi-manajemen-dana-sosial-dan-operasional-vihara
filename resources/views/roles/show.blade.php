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
        <a href="{{ route('roles.index') }}" class="ml-4 text-gray-600 hover:underline">Back to list</a>
    </div>
</div>
@endsection
