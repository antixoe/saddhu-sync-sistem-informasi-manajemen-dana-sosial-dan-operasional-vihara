@extends('layouts.app')

@section('title', 'Add Member')
@section('header', 'New Member Registration')
@section('subtitle', 'Register a new congregation member')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" integrity="sha512-+S0Hf2YQWGWpZJm7x46HWQHIokX1CPG3cs5FqZZ+cRcYfKzvVfMZsql+RfVU07uSjBxPxz3yZnbzUYSvM1z4Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-spiritual p-8">
        <form action="{{ route('members.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user text-saffron"></i> Full Name *
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user-tag text-saffron"></i> Role *
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20">
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profile Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-camera text-saffron"></i> Profile Photo
                    </label>
                    <input type="file" accept="image/*" id="profileImageInput" class="mb-2" />
                    <input type="hidden" name="profile_image" id="profileImageData" value="{{ old('profile_image') }}" />
                    <div id="profilePreview" class="w-32 h-32 rounded-full bg-gray-100 overflow-hidden">
                        <img id="profileImagePreview" src="" class="w-full h-full object-cover hidden" />
                    </div>
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-envelope text-saffron"></i> Email *
                    </label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="md:col-span-2">
                    <label for="password" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-lock text-saffron"></i> Password *
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="password" id="password" required
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                            value="{{ old('password') }}">
                        <button type="button" onclick="generatePassword()" class="px-4 py-2 bg-saffron text-white rounded-lg hover:bg-saffron/90 transition-colors font-medium">
                            <i class="fas fa-refresh"></i> Generate
                        </button>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">
                        <i class="fas fa-info-circle"></i> Share this password with the member for their first login
                    </p>
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-phone text-saffron"></i> Phone
                    </label>
                    <input type="tel" name="phone" id="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('phone') }}">
                </div>

                <!-- Birth Date -->
                <div>
                    <label for="birth_date" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-birthday-cake text-saffron"></i> Birth Date
                    </label>
                    <input type="date" name="birth_date" id="birth_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('birth_date') }}">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map-marker-alt text-saffron"></i> Address
                    </label>
                    <input type="text" name="address" id="address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('address') }}">
                </div>


                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-city text-saffron"></i> City
                    </label>
                    <input type="text" name="city" id="city"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('city') }}">
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map text-saffron"></i> Province
                    </label>
                    <input type="text" name="province" id="province"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('province') }}">
                </div>

                <!-- Postal Code -->
                <div>
                    <label for="postal_code" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-mail-bulk text-saffron"></i> Postal Code
                    </label>
                    <input type="text" name="postal_code" id="postal_code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('postal_code') }}">
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Register Member
                </button>
                <a href="{{ route('members.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    
    <!-- cropping modal -->
    <div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center" style="z-index: 99999; display: none;">
        <div class="bg-white rounded-lg p-6 relative" style="z-index: 100000; width: 90%; max-width: 600px; max-height: 85vh; overflow-y: auto;">
            <button class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-2xl font-bold" onclick="closeCrop()" style="z-index: 100001;">&times;</button>
            <h3 class="text-lg font-semibold text-deep-brown mb-2">Crop Profile Photo</h3>
            <p class="text-xs text-gray-600 mb-4">Drag or resize the crop box. You can move and zoom the image using the guides.</p>
            <div class="bg-gray-200 rounded flex items-center justify-center" style="height: 400px; overflow: hidden; position: relative;">
                <img id="cropperImage" src="" class="max-w-full max-h-full" style="display: block;" />
            </div>
            <div class="mt-6 flex justify-end gap-3" style="z-index: 100001; position: relative;">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50" onclick="closeCrop()">Cancel</button>
                <button type="button" class="px-4 py-2 bg-saffron text-white rounded-lg font-medium hover:bg-orange-600" onclick="applyCrop()">Crop & Save</button>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" integrity="sha512-+/4ODD9CFmQ2wXYSPTDaJCW+U8URq4nqZNcYlVv+bU4VPkCnHQysdOkqD3UBqUGvmV9pUz+Jq3dLdFi78GX4mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function generatePassword() {
    const length = 12;
    const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    document.getElementById('password').value = password;
}

let cropper;
function openCrop(){
    const modal = document.getElementById('cropModal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}
function closeCrop(){
    const modal = document.getElementById('cropModal');
    modal.classList.add('hidden');
    if(cropper) {
        cropper.destroy();
        cropper = null;
    }
}
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
        img.onload = function(){
            openCrop();
            if(cropper) cropper.destroy();
            cropper = new Cropper(img, {aspectRatio:1, viewMode:1, guides:true, highlight:true, background:true});
        };
    };
    reader.readAsDataURL(file);
});
</script>
@endpush