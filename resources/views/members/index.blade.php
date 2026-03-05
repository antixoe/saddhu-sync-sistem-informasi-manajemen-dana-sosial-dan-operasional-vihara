@extends('layouts.app')

@section('title', 'Members')
@section('header', 'Members Management')
@section('subtitle', 'Manage congregation members and their information')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" integrity="sha512-+S0Hf2YQWGWpZJm7x46HWQHIokX1CPG3cs5FqZZ+cRcYfKzvVfMZsql+RfVU07uSjBxPxz3yZnbzUYSvM1z4Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-4">
        <p class="text-gray-600">Total Members: <span class="font-bold text-deep-brown">{{ $members->total() }}</span></p>
        <form method="GET" action="{{ route('members.index') }}" class="flex items-center gap-0">
            <input type="text" name="q" placeholder="Search members..." value="{{ request('q') }}" class="px-3 py-2 border border-gray-300 rounded-l-md text-sm" />
            <button type="submit" class="px-3 py-2 bg-saffron text-white text-sm border-l border-orange-400">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('members.index') }}" class="px-3 py-2 bg-gray-400 text-white rounded-r-md text-sm hover:bg-gray-500">
                <i class="fas fa-times"></i>
            </a>
        </form>
    </div>
    <button onclick="openModal('createMemberModal')" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Add Member</span>
    </button>
</div>

<div class="card-spiritual overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Member ID</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Role</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Name</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Email</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Phone</th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700">Status</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-xs font-mono text-saffron">{{ $member->member_id }}</td>
                        <td class="py-4 px-6 text-gray-800">{{ ucfirst($member->user->role) }}</td>
                        <td class="py-4 px-6">
                            <span class="font-medium text-deep-brown">{{ $member->user->name }}</span>
                        </td>
                        <td class="py-4 px-6 text-gray-600">{{ $member->user->email }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ $member->phone ?? '-' }}</td>                        <td class="py-4 px-6 text-center">
                            @if($member->is_active)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Active</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('members.show', $member) }}" class="text-saffron hover:text-rust text-sm font-medium mr-3">View</a>
                            <a href="{{ route('members.edit', $member) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-3">Edit</a>
                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-gray-600">No members found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $members->links() }}
</div>

<!-- create member modal -->
<div id="createMemberModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="modal-content bg-white rounded-lg w-11/12 max-w-3xl p-8 relative overflow-y-auto max-h-[90vh]">
        <button class="absolute top-4 right-4 text-gray-500 text-2xl hover:text-gray-700" onclick="closeModal('createMemberModal')">&times;</button>
        <h2 class="text-2xl font-semibold text-deep-brown mb-6">New Member Registration</h2>
        <form action="{{ route('members.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="modal_name" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user text-saffron"></i> Full Name *
                    </label>
                    <input type="text" name="name" id="modal_name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="modal_email" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-envelope text-saffron"></i> Email *
                    </label>
                    <input type="email" name="email" id="modal_email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="md:col-span-2">
                    <label for="modal_role" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-user-tag text-saffron"></i> Role *
                    </label>
                    <select name="role" id="modal_role" required
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

                <!-- Phone -->
                <div>
                    <label for="modal_phone" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-phone text-saffron"></i> Phone
                    </label>
                    <input type="tel" name="phone" id="modal_phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('phone') }}">
                </div>

                <!-- Birth Date -->
                <div>
                    <label for="modal_birth_date" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-birthday-cake text-saffron"></i> Birth Date
                    </label>
                    <input type="date" name="birth_date" id="modal_birth_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('birth_date') }}">
                </div>

                <!-- Profile Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-camera text-saffron"></i> Profile Photo
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="file" accept="image/*" id="modalProfileImageInput" class="flex-1" />
                        <div id="modalProfilePreview" class="w-24 h-24 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                            <img id="modalProfileImagePreview" src="" class="w-full h-full object-cover hidden" />
                        </div>
                    </div>
                    <input type="hidden" name="profile_image" id="modalProfileImageData" value="{{ old('profile_image') }}" />
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="modal_address" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map-marker-alt text-saffron"></i> Address
                    </label>
                    <input type="text" name="address" id="modal_address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        placeholder="Click on map to select location"
                        value="{{ old('address') }}">
                </div>



                <!-- City -->
                <div>
                    <label for="modal_city" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-city text-saffron"></i> City
                    </label>
                    <input type="text" name="city" id="modal_city"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('city') }}">
                </div>

                <!-- Province -->
                <div>
                    <label for="modal_province" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-map text-saffron"></i> Province
                    </label>
                    <input type="text" name="province" id="modal_province"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('province') }}">
                </div>

                <!-- Postal Code -->
                <div>
                    <label for="modal_postal_code" class="block text-sm font-semibold text-deep-brown mb-2">
                        <i class="fas fa-mail-bulk text-saffron"></i> Postal Code
                    </label>
                    <input type="text" name="postal_code" id="modal_postal_code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
                        value="{{ old('postal_code') }}">
                </div>
            </div>

            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2 hover:shadow-lg">
                    <i class="fas fa-save"></i> <span>Register Member</span>
                </button>
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50" onclick="closeModal('createMemberModal')">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
    <!-- cropping modal -->
    <div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center" style="z-index: 99999; display: none;">
        <div class="bg-white rounded-lg p-6 relative" style="z-index: 100000; width: 90%; max-width: 600px; max-height: 85vh; overflow-y: auto;">
            <button class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-2xl font-bold" onclick="closeCrop()">&times;</button>
            <h3 class="text-lg font-semibold text-deep-brown mb-2">Crop Profile Photo</h3>
            <p class="text-xs text-gray-600 mb-4">Drag or resize the crop box. You can move and zoom the image using the guides.</p>
            <div class="bg-gray-200 rounded flex items-center justify-center" style="height: 400px; overflow: hidden;">
                <img id="cropperImage" src="" class="max-w-full max-h-full" style="display: block;" />
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50" onclick="closeCrop()">Cancel</button>
                <button type="button" class="px-4 py-2 bg-saffron text-white rounded-lg font-medium hover:bg-orange-600" onclick="applyCrop()">Crop & Save</button>
            </div>
        </div>
    </div>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" integrity="sha512-+/4ODD9CFmQ2wXYSPTDaJCW+U8URq4nqZNcYlVv+bU4VPkCnHQysdOkqD3UBqUGvmV9pUz+Jq3dLdFi78GX4mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
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
    console.log('applyCrop called, cropper exists:', !!cropper);
    if(!cropper) {
        console.error('No cropper instance available');
        return;
    }
    try {
        const canvas = cropper.getCroppedCanvas({width:300, height:300});
        const dataUrl = canvas.toDataURL('image/png');
        
        // Save to the modal form hidden input
        document.getElementById('modalProfileImageData').value = dataUrl;
        console.log('Saved cropped image data, length:', dataUrl.length);
        
        const preview = document.getElementById('modalProfileImagePreview');
        preview.src = dataUrl;
        preview.classList.remove('hidden');
        console.log('Updated preview image');
        
        closeCrop();
    } catch(e) {
        console.error('Error during crop:', e);
    }
}

document.getElementById('modalProfileImageInput').addEventListener('change', function(e){
    const file = e.target.files[0];
    console.log('File selected:', file?.name, 'Size:', file?.size);
    
    if(!file) return;
    const reader = new FileReader();
    reader.onload = function(evt){
        console.log('Image loaded, data length:', evt.target.result.length);
        
        const img = document.getElementById('cropperImage');
        img.src = evt.target.result;
        img.onload = function() {
            console.log('Image onload triggered, opening crop modal');
            openCrop();
            
            // Destroy old cropper if exists
            if(cropper) {
                console.log('Destroying existing cropper');
                cropper.destroy();
            }
            
            // Initialize cropper with proper settings
            setTimeout(() => {
                console.log('Initializing cropper.js');
                cropper = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    restore: true,
                    guides: true,
                    center: true,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: true,
                    background: true,
                    modal: false,
                    ready: function() {
                        console.log('Cropper fully ready and initialized');
                    }
                });
            }, 100);
        };
    };
    reader.readAsDataURL(file);
});
</script>
@endpush