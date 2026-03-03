@extends('layouts.app')

@section('title', 'Add Role')
@section('header', 'Add New Role')
@section('subtitle', 'Create a role that can be assigned to users')

@section('content')
<form action="{{ route('roles.store') }}" method="POST" class="space-y-6 max-w-lg">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Identifier (machine name)</label>
        <input type="text" name="name" id="name" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
               value="{{ old('name') }}">
    </div>

    <div>
        <label for="label" class="block text-sm font-medium text-gray-700">Display Label</label>
        <input type="text" name="label" id="label"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
               value="{{ old('label') }}">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="3"
                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('description') }}</textarea>
    </div>

    <div>
        <button type="submit" class="px-6 py-2 bg-saffron text-white rounded-lg hover:bg-gold transition btn-animated">
            Save Role
        </button>
        <a href="{{ route('roles.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </div>
</form>
@endsection
