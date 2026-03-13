@extends('layouts.app')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-sA+4IuRUIqvQGfdyZFbHOnvczgWBAl3fZAoc2cb5giU=" crossorigin="" />
@endpush

@section('title', 'Donate')
@section('header', 'Make a Donation')
@section('subtitle', 'Support the vihara through QRIS, bank transfer or other virtual payments')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Back button --}}
        <div>
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-saffron hover:text-deep-brown transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        {{-- Instructions pulled from settings --}}
        @if($qrCode)
            <div class="card-spiritual p-6 text-center">
                <h3 class="font-semibold mb-2">Scan QR Code</h3>
                <img src="{{ $qrCode }}" alt="Donation QR code" class="mx-auto max-h-64">
            </div>
        @endif

        @if($bankDetails)
            <div class="card-spiritual p-6">
                <h3 class="font-semibold mb-2">Bank Account</h3>
                <p class="whitespace-pre-line">{!! nl2br(e($bankDetails)) !!}</p>
            </div>
        @endif

        @if($virtualAccounts)
            <div class="card-spiritual p-6">
                <h3 class="font-semibold mb-2">Other Virtual Methods</h3>
                <p class="whitespace-pre-line">{!! nl2br(e($virtualAccounts)) !!}</p>
            </div>
        @endif

        {{-- Donation submission form --}}
        <div class="card-spiritual p-8">
            <form action="{{ route('donate.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="fund_category_id" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sitemap text-saffron"></i> Fund Category *
                    </label>
                    <select name="fund_category_id" id="fund_category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="">-- Select Category --</option>
                        @foreach($fundCategories as $category)
                            <option value="{{ $category->id }}" {{ (old('fund_category_id', $preFillData['fund_category_id'] ?? null) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('fund_category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-coins text-saffron"></i> Amount (Rp) *
                    </label>
                    <input type="number" name="amount" id="amount" required step="0.01" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                           value="{{ old('amount') }}">
                    @error('amount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="donation_method" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-credit-card text-saffron"></i> Method *
                    </label>
                    <select name="donation_method" id="donation_method" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="qris" {{ (old('donation_method', $preFillData['donation_method'] ?? null) == 'qris') ? 'selected' : '' }}>QRIS / QR Code</option>
                        <option value="bank_transfer" {{ (old('donation_method', $preFillData['donation_method'] ?? null) == 'bank_transfer') ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="virtual" {{ (old('donation_method', $preFillData['donation_method'] ?? null) == 'virtual') ? 'selected' : '' }}>Other Virtual Payment</option>
                        <option value="cash" {{ (old('donation_method', $preFillData['donation_method'] ?? null) == 'cash') ? 'selected' : '' }}>Cash</option>
                    </select>
                    @error('donation_method')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_name" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user text-saffron"></i> Contact Name
                    </label>
                    <input type="text" name="contact_name" id="contact_name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                           value="{{ old('contact_name') }}">
                    @error('contact_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-phone text-saffron"></i> Contact Phone
                    </label>
                    <input type="text" name="contact_phone" id="contact_phone"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                           value="{{ old('contact_phone') }}">
                    @error('contact_phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transaction_id" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-receipt text-saffron"></i> Transaction ID / Reference
                    </label>
                    <input type="text" name="transaction_id" id="transaction_id"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                           value="{{ old('transaction_id') }}">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Additional Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes') }}</textarea>
                </div>

                <!-- Address block -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="province" class="block text-sm font-semibold text-deep-brown mb-2">
                            <i class="fas fa-map text-saffron"></i> Province
                        </label>
                        <select name="province" id="province"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                            <option value="">-- Select Province --</option>
                            @if(old('province'))
                                <option value="{{ old('province') }}" selected>{{ old('province') }}</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-semibold text-deep-brown mb-2">
                            <i class="fas fa-city text-saffron"></i> City
                        </label>
                        <select name="city" id="city"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                            <option value="">-- Select City --</option>
                            @if(old('city'))
                                <option value="{{ old('city') }}" selected>{{ old('city') }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-mail-bulk text-saffron"></i> Postal Code
                    </label>
                    <input type="text" name="postal_code" id="postal_code" readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                           value="{{ old('postal_code') }}">
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map-marker-alt text-saffron"></i> Full Address
                    </label>
                    <input type="text" name="address" id="address"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                           value="{{ old('address') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map-marked-alt text-saffron"></i> Select on Map (optional)
                    </label>
                    <div id="donationMap" class="w-full h-64 border border-gray-300"></div>
                </div>

                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                <div class="text-right">
                    <button type="submit" class="px-6 py-2 rounded-lg bg-saffron text-white">
                        Submit Donation
                    </button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
    integrity="sha256-DxE2SSc7Leo+hF+c3eE1vF8abK+lphLMkNwf9e+3kXQ="
    crossorigin=""></script>
<script>
    // minimal province-city-postal dataset
    const locationData = {
        "DKI Jakarta": {
            "Jakarta Selatan": "12210",
            "Jakarta Barat": "11470"
        },
        "Jawa Barat": {
            "Bandung": "40115",
            "Bogor": "16111"
        },
        "Jawa Timur": {
            "Surabaya": "60231",
            "Malang": "65111"
        }
    };

    const provinceEl = document.getElementById('province');
    const cityEl = document.getElementById('city');
    const postalEl = document.getElementById('postal_code');
    const addressEl = document.getElementById('address');
    const latEl = document.getElementById('latitude');
    const lonEl = document.getElementById('longitude');

    // populate provinces
    Object.keys(locationData).forEach(p => {
        const opt = document.createElement('option');
        opt.value = p;
        opt.textContent = p;
        provinceEl.appendChild(opt);
    });

    provinceEl.addEventListener('change', () => {
        cityEl.innerHTML = '<option value="">-- Select City --</option>';
        postalEl.value = '';
        const cities = locationData[provinceEl.value] || {};
        Object.keys(cities).forEach(c => {
            const opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            cityEl.appendChild(opt);
        });
    });

    cityEl.addEventListener('change', () => {
        const prov = provinceEl.value;
        if (prov && locationData[prov][cityEl.value]) {
            postalEl.value = locationData[prov][cityEl.value];
        } else {
            postalEl.value = '';
        }
    });

    // initialize map
    const map = L.map('donationMap').setView([-6.200000, 106.816666], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    let marker;

    // if old coordinates exist, show marker
    const oldLat = parseFloat(latEl.value);
    const oldLon = parseFloat(lonEl.value);
    if (!isNaN(oldLat) && !isNaN(oldLon)) {
        marker = L.marker([oldLat, oldLon]).addTo(map);
        map.setView([oldLat, oldLon], 13);
    }

    function onMapClick(e) {
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
        latEl.value = e.latlng.lat.toFixed(7);
        lonEl.value = e.latlng.lng.toFixed(7);
        // reverse geocode
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
            .then(r => r.json())
            .then(data => {
                if (data.address) {
                    addressEl.value = data.display_name || '';
                    if (data.address.state) provinceEl.value = data.address.state;
                    if (data.address.city || data.address.town || data.address.village) {
                        cityEl.value = data.address.city || data.address.town || data.address.village;
                    }
                    // attempt postal
                    if (data.address.postcode) postalEl.value = data.address.postcode;
                }
            });
    }
    map.on('click', onMapClick);
</script>
@endpush

@endsection
