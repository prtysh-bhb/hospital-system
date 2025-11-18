@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->full_name }}</h1>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Patient dashboard coming soon...</p>
        </div>
    </div>
@endsection
