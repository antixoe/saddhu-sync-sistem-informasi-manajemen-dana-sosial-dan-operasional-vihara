@extends('layouts.app')

@section('title', 'Roles')
@section('header', 'Role Management')
@section('subtitle', 'Define and manage user roles for the system')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-4">
        <p class="text-gray-600">Total Roles: <span class="font-bold text-deep-brown">{{ $roles->total() }}</span></p>
        <form method="GET" action="{{ route('roles.index') }}" class="flex items-center gap-0">
            <input type="text" name="q" placeholder="Search roles..." value="{{ request('q') }}" class="px-3 py-2 border border-gray-300 rounded-l-md text-sm" />
            <button type="submit" class="px-3 py-2 bg-saffron text-white text-sm border-l border-orange-400">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('roles.index') }}" class="px-3 py-2 bg-gray-400 text-white rounded-r-md text-sm hover:bg-gray-500">
                <i class="fas fa-times"></i>
            </a>
        </form>
    </div>
    <button onclick="openModal('createRoleModal')" class="btn-spiritual px-6 py-2 text-white rounded-lg font-medium flex items-center space-x-2">
        <i class="fas fa-plus"></i>
        <span>Add Role</span>
    </button>
</div>

<div class="card-spiritual overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Name</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Label</th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700">Description</th>
                    <th class="text-right py-4 px-6 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-deep-brown font-medium">{{ $role->name }}</td>
                        <td class="py-4 px-6">{{ $role->label ?? '-' }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ Illuminate\Support\Str::limit($role->description, 60) }}</td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('roles.edit', $role) }}" class="text-saffron hover:text-rust text-sm font-medium">Edit</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 px-6 text-center text-gray-600">No roles defined</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $roles->links() }}
</div>

<!-- create role modal -->
<div id="createRoleModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white rounded-lg w-11/12 max-w-lg p-8 relative overflow-auto max-h-[90vh]">
        <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('createRoleModal')">&times;</button>
        <h2 class="text-2xl font-semibold text-deep-brown mb-4">Add New Role</h2>
        <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
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
                <button type="button" class="ml-4 text-gray-600 hover:underline" onclick="closeModal('createRoleModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
