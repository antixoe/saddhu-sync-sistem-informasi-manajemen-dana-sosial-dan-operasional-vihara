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
                        <label class="block text-sm font-medium text-gray-700">Google Maps API Key (Optional)</label>
                        <input name="google_maps_key" type="text" value="{{ old('google_maps_key', $settings['google_maps_key'] ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500">No longer needed - using OpenStreetMap (free) instead.</p>
                    </div>

                    <!-- donation config -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Donation QR Code</label>
                        <input name="donation_qr_code" type="text" value="{{ old('donation_qr_code', $settings['donation_qr_code'] ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500">Link to QR image (could be data URI) shown on public donate page.</p>
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Bank Account Details</label>
                        <textarea name="donation_bank_details" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('donation_bank_details', $settings['donation_bank_details'] ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Plain text information visitors can copy.</p>
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Other Virtual Payments</label>
                        <textarea name="donation_virtual_accounts" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('donation_virtual_accounts', $settings['donation_virtual_accounts'] ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Additional methods (e.g. e-wallets) to display on donate page.</p>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-saffron text-white">Save Settings</button>
                </div>
            </div>
        </form>
    </div>
@endsection
