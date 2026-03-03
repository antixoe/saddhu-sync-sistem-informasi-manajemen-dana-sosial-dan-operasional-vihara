@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<h2 class="text-2xl font-bold text-deep-brown mb-6">Welcome Back</h2>

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-red-700 text-sm font-medium mb-2">Login failed</p>
        @foreach ($errors->all() as $error)
            <p class="text-red-600 text-xs">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('login') }}" method="POST" class="space-y-4">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-semibold text-deep-brown mb-2">
            <i class="fas fa-envelope text-saffron"></i> Email
        </label>
        <input type="email" name="email" id="email" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
            value="{{ old('email') }}"
            placeholder="your@email.com">
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-semibold text-deep-brown mb-2">
            <i class="fas fa-lock text-saffron"></i> Password
        </label>
        <input type="password" name="password" id="password" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-saffron focus:ring-2 focus:ring-saffron/20"
            placeholder="Enter your password">
    </div>

    <!-- Remember Me -->
    <div class="flex items-center">
        <input type="checkbox" name="remember" id="remember" class="rounded">
        <label for="remember" class="ml-2 text-sm text-gray-700">Remember me</label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full mt-6 py-3 bg-gradient-to-r from-saffron to-gold text-white font-bold rounded-lg hover:shadow-lg transition btn-animated">
        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
    </button>
    <div class="mt-2 text-center">
        <a href="{{ route('register') }}" class="text-sm text-saffron hover:underline">Don't have an account? Register</a>
    </div>
</form>

<div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
    <p class="text-sm text-gray-700 mb-2">Demo Credentials:</p>
    <p class="text-xs text-gray-600"><strong>Email:</strong> admin@saddhusync.local</p>
    <p class="text-xs text-gray-600"><strong>Password:</strong> password</p>
</div>
@endsection
