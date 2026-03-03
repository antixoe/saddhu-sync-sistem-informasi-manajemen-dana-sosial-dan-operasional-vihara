@extends('layouts.app')

@section('title', 'Edit Member')
@section('header', 'Edit Member: ' . $member->user->name)
@section('subtitle', 'Update member information')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" integrity="sha512-+S0Hf2YQWGWpZJm7x46HWQHIokX1CPG3cs5FqZZ+cRcYfKzvVfMZsql+RfVU07uSjBxPxz3yZnbzUYSvM1z4Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
        <form action="{{ route('members.update', $member) }}" method="POST" class="space-y-6">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user text-saffron"></i> Full Name
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('name', $member->user->name) }}">
                </div>

                <!-- Role -->
                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user-tag text-saffron"></i> Role
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ old('role', $member->user->role) == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Profile Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-camera text-saffron"></i> Profile Photo
                    </label>
                    <input type="file" accept="image/*" id="profileImageInput" class="mb-2" />
                    <input type="hidden" name="profile_image" id="profileImageData" value="{{ old('profile_image', $member->profile_image ? asset('storage/'.$member->profile_image) : '') }}" />
                    <div id="profilePreview" class="w-32 h-32 rounded-full bg-gray-100 overflow-hidden">
                        <img id="profileImagePreview" src="{{ $member->profile_image ? asset('storage/'.$member->profile_image) : '' }}" class="w-full h-full object-cover {{ $member->profile_image ? '' : 'hidden' }}" />
                    </div>
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-envelope text-saffron"></i> Email
                    </label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('email', $member->user->email) }}">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-phone text-saffron"></i> Phone
                    </label>
                    <input type="tel" name="phone" id="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('phone', $member->phone) }}">
                </div>

                <!-- Birth Date -->
                <div>
                    <label for="birth_date" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-birthday-cake text-saffron"></i> Birth Date
                    </label>
                    <input type="date" name="birth_date" id="birth_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map-marker-alt text-saffron"></i> Address
                    </label>
                    <input type="text" name="address" id="address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('address', $member->address) }}">
                </div>

                <!-- Coordinates and map picker -->
                <div class="md:col-span-2">
                    <div id="map" class="w-full h-64 rounded-lg mb-2"></div>
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $member->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $member->longitude) }}">
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-city text-saffron"></i> City
                    </label>
                    <input type="text" name="city" id="city"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('city', $member->city) }}">
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map text-saffron"></i> Province
                    </label>
                    <input type="text" name="province" id="province"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('province', $member->province) }}">
                </div>

                <!-- Postal Code -->
                <div>
                    <label for="postal_code" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-mail-bulk text-saffron"></i> Postal Code
                    </label>
                    <input type="text" name="postal_code" id="postal_code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('postal_code', $member->postal_code) }}">
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-sticky-note text-saffron"></i> Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">{{ old('notes', $member->notes) }}</textarea>
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
                <a href="{{ route('members.show', $member) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

    <!-- cropping modal -->
    <div id="cropModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="modal-content bg-white rounded-lg w-full max-w-lg p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeCrop()">&times;</button>
            <h3 class="text-lg font-semibold text-deep-brown mb-2">Crop Profile Photo</h3>
            <div class="overflow-auto">
                <img id="cropperImage" src="" class="max-w-full" />
            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <button type="button" class="btn-spiritual px-4 py-2 text-white rounded" onclick="applyCrop()">Crop</button>
                <button type="button" class="px-4 py-2 border rounded" onclick="closeCrop()">Cancel</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function initMemberMap() {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    let lat = parseFloat(latInput.value) || -6.200000;
    let lng = parseFloat(lngInput.value) || 106.816666;
    const map = new google.maps.Map(document.getElementById('map'), { center: {lat, lng}, zoom: 13 });
    const marker = new google.maps.Marker({ position: {lat, lng}, map: map, draggable: true });
    marker.addListener('dragend', function(e) {
        latInput.value = e.latLng.lat();
        lngInput.value = e.latLng.lng();
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: e.latLng }, function(results, status) {
            if (status === 'OK' && results[0]) {
                document.getElementById('address').value = results[0].formatted_address;
            }
        });
    });
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMemberMap&libraries=places"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" integrity="sha512-+/4ODD9CFmQ2wXYSPTDaJCW+U8URq4nqZNcYlVv+bU4VPkCnHQysdOkqD3UBqUGvmV9pUz+Jq3dLdFi78GX4mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
let cropper;
function openCrop(){ document.getElementById('cropModal').classList.add('active'); }
function closeCrop(){ document.getElementById('cropModal').classList.remove('active'); }
function applyCrop(){
    if(!cropper) return;
    const canvas = cropper.getCroppedCanvas({width:300,height:300});
    const dataUrl = canvas.toDataURL('image/png');
    document.getElementById('profileImageData').value = dataUrl;
    const preview = document.getElementById('profileImagePreview');
    preview.src = dataUrl;
    preview.classList.remove('hidden');
    closeCrop();
}

document.getElementById('profileImageInput').addEventListener('change', function(e){
    const file = e.target.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = function(evt){
        const img = document.getElementById('cropperImage');
        img.src = evt.target.result;
        openCrop();
        if(cropper) cropper.destroy();
        cropper = new Cropper(img, {aspectRatio:1, viewMode:1});
    };
    reader.readAsDataURL(file);
});
</script>
@endpush