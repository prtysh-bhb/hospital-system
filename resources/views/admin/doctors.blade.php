@extends('layouts.admin')

@section('title', 'Doctors Management')

@section('page-title', 'Doctors Management')

@section('header-actions')
    <a href="{{ route('admin.doctor-add') }}"
        class="px-4 sm:px-6 py-2 text-sm sm:text-base text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">+ Add
        Doctor</a>
@endsection

@section('content')
    <!-- Success Message -->
    @if (session('success'))
        <div
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
            <div class="md:col-span-2">
                <input type="text" id="searchInput" placeholder="Search by name, specialty, email, phone..."
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
            <div>
                <select id="specialtyFilter"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option value="">All Specialties</option>
                    @foreach ($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select id="statusFilter"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div>
        <p class="mt-2 text-sm text-gray-600">Loading doctors...</p>
    </div>

    <!-- Doctors Grid -->
    <div id="doctorsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @include('admin.partials.doctor-cards', ['doctors' => $doctors])
    </div>

    <script>
        let searchTimeout;

        function fetchDoctors() {
            const search = document.getElementById('searchInput').value;
            const specialty_id = document.getElementById('specialtyFilter').value;
            const status = document.getElementById('statusFilter').value;

            // Show loading indicator
            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('doctorsGrid').style.opacity = '0.5';

            // Build query parameters
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (specialty_id) params.append('specialty_id', specialty_id);
            if (status) params.append('status', status);

            // Make AJAX request
            fetch(`{{ route('admin.doctors') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('doctorsGrid').innerHTML = html;
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    document.getElementById('doctorsGrid').style.opacity = '1';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    document.getElementById('doctorsGrid').style.opacity = '1';
                    alert('An error occurred while fetching doctors. Please try again.');
                });
        }

        // Search input with debounce
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchDoctors();
            }, 500); // Wait 500ms after user stops typing
        });

        // Specialty filter
        document.getElementById('specialtyFilter').addEventListener('change', function() {
            fetchDoctors();
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            fetchDoctors();
        });
    </script>
@endsection
