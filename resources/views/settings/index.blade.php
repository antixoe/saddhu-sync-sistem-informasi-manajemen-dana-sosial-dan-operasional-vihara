@extends('layouts.app')

@section('title','Settings')

@section('header','Settings')
@section('subtitle','Application configuration')

@section('content')
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="card-spiritual p-6">
                <h3 class="text-lg font-semibold mb-4">General</h3>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input name="site_name" type="text" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Support Email</label>
                        <input name="support_email" type="email" value="{{ old('support_email', $settings['support_email'] ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Default Locale</label>
                        <input name="default_locale" type="text" value="{{ old('default_locale', $settings['default_locale'] ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Google Maps API Key</label>
                        <input name="google_maps_key" type="text" value="{{ old('google_maps_key', $settings['google_maps_key'] ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500">Used by map pickers and geocoding features.</p>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-saffron text-white">Save Settings</button>
                </div>
            </div>
        </form>
    </div>
@endsection
