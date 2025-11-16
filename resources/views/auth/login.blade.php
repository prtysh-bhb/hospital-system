@extends('layouts.public')

@section('title', 'Login')
@section('body-class', 'bg-gradient-to-br from-sky-50 via-white to-sky-50 min-h-screen flex items-center justify-center p-4')

@section('content')
<div class="w-full max-w-md">
    <!-- Logo & Title -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-sky-600 rounded-2xl mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">MediCare HMS</h1>
        <p class="text-gray-600">Hospital Management System</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Sign In</h2>

        <form>
            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input
                    type="email"
                    placeholder="admin@hospital.com"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                >
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input
                    type="password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                >
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" class="w-4 h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-sky-600 hover:text-sky-700 font-medium">Forgot password?</a>
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="w-full py-3 px-4 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg transition-colors"
            >
                Sign In
            </button>
        </form>

        <!-- Role Selector -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-600 text-center mb-3">Select your role</p>
            <div class="grid grid-cols-3 gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm text-center border border-gray-300 rounded-lg hover:border-sky-500 hover:text-sky-600 transition-colors">
                    Admin
                </a>
                <a href="{{ route('doctor.dashboard') }}" class="px-4 py-2 text-sm text-center border border-gray-300 rounded-lg hover:border-sky-500 hover:text-sky-600 transition-colors">
                    Doctor
                </a>
                <a href="{{ route('frontdesk.dashboard') }}" class="px-4 py-2 text-sm text-center border border-gray-300 rounded-lg hover:border-sky-500 hover:text-sky-600 transition-colors">
                    Front Desk
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-6">
        <a href="{{ route('home') }}" class="text-sm text-sky-600 hover:text-sky-700 font-medium">
            ← Back to Home
        </a>
    </div>
    <p class="text-center text-sm text-gray-600 mt-4">
        © 2024 MediCare HMS. All rights reserved.
    </p>
</div>
@endsection
