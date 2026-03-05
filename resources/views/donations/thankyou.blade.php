@extends('layouts.app')

@section('title','Thank You')
@section('header','Thank You for Your Donation')
@section('subtitle','We appreciate your support')

@section('content')
    <div class="max-w-xl mx-auto text-center space-y-4">
        <p class="text-lg">
            Your contribution helps us continue our mission. A member of our team will verify the payment and
            send a receipt if you provided any contact information.
        </p>
        <a href="/" class="inline-block px-6 py-2 bg-saffron rounded-lg text-white">Return to home</a>
    </div>
@endsection
