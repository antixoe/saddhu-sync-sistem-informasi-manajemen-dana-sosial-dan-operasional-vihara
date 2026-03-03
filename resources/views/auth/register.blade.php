@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="animate-fade-in-up">
    <h2 class="text-2xl font-bold text-deep-brown mb-6">Create Account</h2>

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-red-700 text-sm font-medium mb-2">Registration failed</p>
        @foreach ($errors->all() as $error)
            <p class="text-red-600 text-xs">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('register.post') }}" method="POST" class="space-y-4">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-semibold text-deep-brown mb-2">
            <i class="fas fa-user text-saffron"></i> Name
        </label>
        <input type="text" name="name" id="name" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
            value="{{ old('name') }}" placeholder="Your full name">
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-semibold text-deep-brown mb-2">
            <i class="fas fa-envelope text-saffron"></i> Email
        </label>
        <input type="email" name="email" id="email" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
            value="{{ old('email') }}" placeholder="you@example.com">
    </div>

    <!-- Password -->
    <div class="relative">
        <label for="password" class="block text-sm font-semibold text-deep-brown mb-2">
            <i class="fas fa-lock text-saffron"></i> Password
        </label>
        <input type="password" name="password" id="password" required
            class="w-full px-4 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
            placeholder="Choose a strong password">
        <button type="button" onclick="togglePassword('password','eyeIconReg')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 z-20 password-toggle focus:outline-none">
            <i id="eyeIconReg" class="fas fa-eye"></i>
        </button>
    </div>

    <!-- Confirm Password -->
    <div class="relative">
        <label for="password_confirmation" class="block text-sm font-semibold text-deep-brown mb-2">
            <i class="fas fa-lock text-saffron"></i> Confirm Password
        </label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            class="w-full px-4 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
            placeholder="Re-enter password">
        <button type="button" onclick="togglePassword('password_confirmation','eyeIconConf')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 z-20 password-toggle focus:outline-none">
            <i id="eyeIconConf" class="fas fa-eye"></i>
        </button>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full mt-4 py-3 bg-saffron text-white rounded-lg font-bold hover:bg-gold transition btn-animated">
        <i class="fas fa-user-plus mr-2"></i> Register
    </button>
</form>

<div class="mt-6 text-center">
    <p class="text-sm">Already have an account? <a href="{{ route('login') }}" class="text-saffron hover:underline">Sign in</a></p>
</div>
</div>
@endsection
