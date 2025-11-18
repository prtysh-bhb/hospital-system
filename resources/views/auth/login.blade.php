@extends('layouts.public')

@section('title', 'Login')
@section('body-class', 'bg-gradient-to-br from-sky-50 via-white to-sky-50 min-h-screen flex items-center justify-center
    p-4')

@section('content')
    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-6 sm:mb-8">
            <div
                class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-sky-600 rounded-2xl mb-3 sm:mb-4">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">MediCare HMS</h1>
            <p class="text-sm sm:text-base text-gray-600">Hospital Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Sign In</h2>

            <!-- Alert Message -->
            <div id="alert-message" class="hidden mb-4 p-3 rounded-lg text-sm"></div>

            <form id="login-form">
                @csrf
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="admin@hospital.com"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                        required>
                    <span class="text-red-500 text-xs mt-1 hidden" id="email-error"></span>
                </div>

                <!-- Password -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                        required>
                    <span class="text-red-500 text-xs mt-1 hidden" id="password-error"></span>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
                        <span class="ml-2 text-xs sm:text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-xs sm:text-sm text-sky-600 hover:text-sky-700 font-medium">Forgot
                        password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" id="login-btn"
                    class="w-full py-2.5 sm:py-3 px-4 text-sm sm:text-base bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                    <span id="btn-text">Sign In</span>
                    <svg id="btn-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </form>

        </div>

        <!-- Footer -->
        <div class="text-center mt-4 sm:mt-6">
            <a href="{{ route('home') }}" class="text-xs sm:text-sm text-sky-600 hover:text-sky-700 font-medium">
                ← Back to Home
            </a>
        </div>
        <p class="text-center text-xs sm:text-sm text-gray-600 mt-3 sm:mt-4">
            © 2025 MediCare HMS. All rights reserved.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const loginBtn = document.getElementById('login-btn');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const alertMessage = document.getElementById('alert-message');

            // Clear error messages
            function clearErrors() {
                document.getElementById('email-error').classList.add('hidden');
                document.getElementById('password-error').classList.add('hidden');
                document.getElementById('email').classList.remove('border-red-500');
                document.getElementById('password').classList.remove('border-red-500');
            }

            // Show alert message
            function showAlert(message, type = 'error') {
                alertMessage.className =
                    `mb-4 p-3 rounded-lg text-sm ${type === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'}`;
                alertMessage.textContent = message;
                alertMessage.classList.remove('hidden');

                // Auto hide after 5 seconds
                setTimeout(() => {
                    alertMessage.classList.add('hidden');
                }, 5000);
            }

            // Show field error
            function showFieldError(field, message) {
                const errorElement = document.getElementById(`${field}-error`);
                const inputElement = document.getElementById(field);

                if (errorElement && inputElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                    inputElement.classList.add('border-red-500');
                }
            }

            // Handle form submission
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Clear previous errors
                clearErrors();
                alertMessage.classList.add('hidden');

                // Disable button and show loading
                loginBtn.disabled = true;
                btnText.textContent = 'Signing In...';
                btnSpinner.classList.remove('hidden');

                // Get form data
                const formData = new FormData(loginForm);
                const data = {
                    email: formData.get('email'),
                    password: formData.get('password'),
                    remember: formData.get('remember') ? true : false
                };

                try {
                    const response = await fetch('{{ route('login.post') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                                .value,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Show success message
                        showAlert('Login successful! Redirecting...', 'success');

                        // Redirect after short delay
                        setTimeout(() => {
                            window.location.href = result.redirect_url;
                        }, 1000);
                    } else {
                        // Handle validation errors
                        if (result.errors) {
                            Object.keys(result.errors).forEach(field => {
                                showFieldError(field, result.errors[field][0]);
                            });
                        }

                        // Show general error message
                        showAlert(result.message || 'Login failed. Please check your credentials.');

                        // Re-enable button
                        loginBtn.disabled = false;
                        btnText.textContent = 'Sign In';
                        btnSpinner.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    showAlert('An unexpected error occurred. Please try again.');

                    // Re-enable button
                    loginBtn.disabled = false;
                    btnText.textContent = 'Sign In';
                    btnSpinner.classList.add('hidden');
                }
            });

            // Clear error on input
            document.getElementById('email').addEventListener('input', () => {
                document.getElementById('email-error').classList.add('hidden');
                document.getElementById('email').classList.remove('border-red-500');
            });

            document.getElementById('password').addEventListener('input', () => {
                document.getElementById('password-error').classList.add('hidden');
                document.getElementById('password').classList.remove('border-red-500');
            });
        });
    </script>
@endsection
