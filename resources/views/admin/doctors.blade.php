@extends('layouts.admin')

@section('title', 'Doctors Management')

@section('page-title', 'Doctors Management')

@section('header-actions')
    <div class="relative inline-block text-left">
        <!-- Dropdown Button -->
        <button type="button"
            class="inline-flex items-center gap-2 px-5 py-2 text-sm sm:text-base font-medium text-white bg-gray-800 hover:bg-gray-900 rounded-lg focus:outline-none"
            onclick="document.getElementById('doctorActionsDropdown').classList.toggle('hidden')">
            Actions
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div id="doctorActionsDropdown"
            class="hidden absolute right-0 z-10 mt-2 w-52 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="py-1 text-sm text-gray-700">
                <!-- Export CSV -->
                <a href="{{ route('admin.doctors.export-csv') }}"
                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Export CSV
                </a>

                <!-- Bulk Import -->
                <button onclick="openImportModal()"
                    class="w-full flex items-center gap-2 px-4 py-2 hover:bg-gray-100 text-left">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Bulk Import
                </button>

                <!-- Divider -->
                <div class="my-1 border-t"></div>

                <!-- Add Doctor -->
                <a href="{{ route('admin.doctors.add') }}"
                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 font-medium text-sky-600">
                    + Add Doctor
                </a>
            </div>
        </div>
    </div>

@endsection

@section('content')
    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Doctor View Modal -->
    <div id="doctorViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div id="modalHeader"
                class="bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">Doctor Details</h2>
                <button onclick="closeDoctorViewModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                <!-- Loading State -->
                <div id="doctorViewLoading" class="p-8 text-center">
                    <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-sky-600"></div>
                    <p class="mt-3 text-gray-600">Loading doctor details...</p>
                </div>

                <!-- Doctor Details Content -->
                <div id="doctorViewContent" class="hidden">
                    <!-- Profile Section -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                            <img id="doctorImage"
                                class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg object-cover"
                                alt="Doctor Profile"
                                onerror="this.onerror=null; this.src=`https://ui-avatars.com/api/?name=${encodeURIComponent(this.dataset.name || 'Doctor')}&background=0ea5e9&color=fff&size=128`;">
                            <div class="text-center sm:text-left flex-1">
                                <h3 id="doctorName" class="text-2xl font-bold text-gray-800"></h3>
                                <p id="doctorSpecialty" class="text-sky-600 font-semibold mt-1"></p>
                                <p id="doctorQualification" class="text-gray-600 text-sm mt-1"></p>
                                <div class="flex flex-wrap gap-2 mt-3 justify-center sm:justify-start">
                                    <span id="doctorStatus" class="px-3 py-1 text-xs font-medium rounded-full"></span>
                                    <span id="doctorAvailability" class="px-3 py-1 text-xs font-medium rounded-full"></span>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-3 gap-4 p-6 bg-gray-50">
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-gray-100">
                            <p id="totalAppointments" class="text-2xl font-bold text-sky-600">0</p>
                            <p class="text-xs text-gray-500 mt-1">Total Appointments</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-gray-100">
                            <p id="completedAppointments" class="text-2xl font-bold text-green-600">0</p>
                            <p class="text-xs text-gray-500 mt-1">Completed</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-gray-100">
                            <p id="upcomingAppointments" class="text-2xl font-bold text-amber-600">0</p>
                            <p class="text-xs text-gray-500 mt-1">Upcoming</p>
                        </div>
                    </div>

                    <!-- Personal & Professional Info -->
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Personal Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Email:</span>
                                    <span id="doctorEmail" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Username:</span>
                                    <span id="doctorUsername" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Phone:</span>
                                    <span id="doctorPhone" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Gender:</span>
                                    <span id="doctorGender" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Date of Birth:</span>
                                    <span id="doctorDob" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Address:</span>
                                    <span id="doctorAddress" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div>
                            <h4
                                class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Professional Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Experience:</span>
                                    <span id="doctorExperience" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">License No:</span>
                                    <span id="doctorLicense" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Consultation:</span>
                                    <span id="doctorFee" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-gray-500 text-sm w-28 flex-shrink-0">Joined:</span>
                                    <span id="doctorJoined" class="text-gray-800 text-sm font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Section -->
                    <div class="p-6 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Weekly Schedule
                        </h4>
                        <div id="doctorSchedules" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Schedules will be populated here -->
                        </div>
                        <div id="noSchedules" class="hidden text-center py-4 text-gray-500 text-sm">
                            No schedules configured for this doctor.
                        </div>
                    </div>

                    <!-- Bio Section -->
                    <div class="p-6 border-t border-gray-100 bg-gray-50">
                        <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            About
                        </h4>
                        <p id="doctorBio" class="text-gray-600 text-sm leading-relaxed"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeDoctorViewModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Close
                </button>
                <a id="editDoctorBtn" href="#"
                    class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors bg-sky-600 hover:bg-sky-700">
                    Edit Doctor
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
            <div class="md:col-span-2">
                <input type="text" id="searchInput" placeholder="Search by name, specialty, email, phone..."
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300">
            </div>
            <div>
                <select id="specialtyFilter"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300">
                    <option value="">All Specialties</option>
                    @foreach ($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select id="statusFilter"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                    {{-- <option value="on_leave">On Leave</option> --}}
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

    <!-- Custom Delete Modal -->
    <div id="customDeleteModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-80">
            <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
            <p id="deleteModalText" class="text-gray-700 mb-6">Are you sure you want to delete this doctor?</p>

            <div class="flex justify-end space-x-3">
                <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">
                    Cancel
                </button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;

        document.addEventListener("DOMContentLoaded", function() {
            $(document).on('click', '.delete-doctor-btn', function() {
                deleteDoctorId = $(this).data('doctor-id');
                let doctorName = $(this).data('doctor-name');

                document.getElementById('deleteModalText').textContent =
                    `Are you sure you want to delete the doctor "${doctorName}"?`;

                document.getElementById('customDeleteModal').classList.remove('hidden');
            });

            // Cancel button closes modal
            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                document.getElementById('customDeleteModal').classList.add('hidden');
                deleteDoctorId = null;
            });
        });

        // Notification function
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
                'bg-red-100 border-red-400 text-red-700';

            notification.className =
                `${bgColor} border px-4 py-3 rounded-lg shadow-lg flex items-center justify-between min-w-[300px]`;
            notification.innerHTML = `
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            container.appendChild(notification);
            setTimeout(() => notification.remove(), 5000);
        }

        function fetchDoctors() {
            const search = document.getElementById('searchInput').value;
            const specialty_id = document.getElementById('specialtyFilter').value;
            const status = document.getElementById('statusFilter').value;

            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('doctorsGrid').style.opacity = '0.5';

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (specialty_id) params.append('specialty_id', specialty_id);
            if (status) params.append('status', status);

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
                    attachDeleteHandlers();
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    document.getElementById('doctorsGrid').style.opacity = '1';
                    showNotification('An error occurred while fetching doctors.', 'error');
                });
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!deleteDoctorId) return;
            fetch(`/admin/doctors/${deleteDoctorId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        fetchDoctors();
                        document.getElementById('customDeleteModal').classList.add('hidden');
                    } else {
                        toastr.error(data.message || 'Failed to delete doctor', 'error');
                    }
                })
                .catch(error => {
                    toastr.error('An error occurred while deleting the doctor.', 'error');
                });

        });

        // Color schemes matching doctor-cards.blade.php
        const colorSchemes = [{
                gradient: 'from-slate-700 to-slate-800',
                avatar: '475569',
                text: 'text-slate-700',
                accent: 'bg-slate-700',
                accentLight: 'bg-slate-50',
                border: 'border-slate-200',
                hover: 'hover:bg-slate-800',
                scheduleCard: 'bg-slate-50 border-slate-100 text-slate-700'
            },
            {
                gradient: 'from-blue-900 to-blue-950',
                avatar: '1e3a8a',
                text: 'text-blue-900',
                accent: 'bg-blue-900',
                accentLight: 'bg-blue-50',
                border: 'border-blue-200',
                hover: 'hover:bg-blue-950',
                scheduleCard: 'bg-blue-50 border-blue-100 text-blue-900'
            },
            {
                gradient: 'from-teal-800 to-teal-900',
                avatar: '115e59',
                text: 'text-teal-800',
                accent: 'bg-teal-800',
                accentLight: 'bg-teal-50',
                border: 'border-teal-200',
                hover: 'hover:bg-teal-900',
                scheduleCard: 'bg-teal-50 border-teal-100 text-teal-800'
            },
            {
                gradient: 'from-gray-700 to-gray-800',
                avatar: '4b5563',
                text: 'text-gray-700',
                accent: 'bg-gray-700',
                accentLight: 'bg-gray-50',
                border: 'border-gray-200',
                hover: 'hover:bg-gray-800',
                scheduleCard: 'bg-gray-50 border-gray-100 text-gray-700'
            },
            {
                gradient: 'from-indigo-900 to-indigo-950',
                avatar: '312e81',
                text: 'text-indigo-900',
                accent: 'bg-indigo-900',
                accentLight: 'bg-indigo-50',
                border: 'border-indigo-200',
                hover: 'hover:bg-indigo-950',
                scheduleCard: 'bg-indigo-50 border-indigo-100 text-indigo-900'
            }
        ];

        let currentModalColor = null;

        function attachDeleteHandlers() {
            // Attach view details handlers
            document.querySelectorAll('.view-doctor-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const doctorId = this.dataset.doctorId;
                    // Get color data from button
                    const colorData = {
                        gradient: this.dataset.colorGradient,
                        avatar: this.dataset.colorAvatar,
                        text: this.dataset.colorText,
                        accent: this.dataset.colorAccent,
                        accentLight: this.dataset.colorAccentLight,
                        border: this.dataset.colorBorder,
                        hover: this.dataset.colorHover
                    };
                    viewDoctorDetails(doctorId, colorData);
                });
            });
        }

        // View Doctor Details Modal Functions
        function viewDoctorDetails(doctorId, colorData) {
            const modal = document.getElementById('doctorViewModal');
            const loading = document.getElementById('doctorViewLoading');
            const content = document.getElementById('doctorViewContent');
            const modalHeader = document.getElementById('modalHeader');

            // Store current color for use in populateDoctorModal
            currentModalColor = colorData;

            // Apply color to modal header
            if (colorData && colorData.gradient) {
                modalHeader.className =
                    `bg-gradient-to-r ${colorData.gradient} px-6 py-4 flex justify-between items-center`;
            } else {
                modalHeader.className =
                    'bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-4 flex justify-between items-center';
            }

            // Show modal with loading state
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            loading.classList.remove('hidden');
            content.classList.add('hidden');

            fetch(`/admin/doctors/${doctorId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateDoctorModal(data);
                        loading.classList.add('hidden');
                        content.classList.remove('hidden');
                    } else {
                        showNotification(data.message || 'Failed to load doctor details', 'error');
                        closeDoctorViewModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while loading doctor details.', 'error');
                    closeDoctorViewModal();
                });
        }

        function populateDoctorModal(data) {
            const doctor = data.doctor;
            const schedules = data.schedules;
            const stats = data.statistics;

            // Get avatar background color from current modal color or default
            const avatarBg = currentModalColor && currentModalColor.avatar ? currentModalColor.avatar : '0ea5e9';

            // Profile Image with fallback
            const imgEl = document.getElementById('doctorImage');
            imgEl.dataset.name = doctor.full_name; // Set data-name for onerror fallback

            // Update onerror to use current color
            imgEl.onerror = function() {
                this.onerror = null;
                this.src =
                    `https://ui-avatars.com/api/?name=${encodeURIComponent(this.dataset.name || 'Doctor')}&background=${avatarBg}&color=fff&size=128`;
            };

            if (doctor.profile_image) {
                imgEl.src = doctor.profile_image;
            } else {
                imgEl.src =
                    `https://ui-avatars.com/api/?name=${encodeURIComponent(doctor.full_name)}&background=${avatarBg}&color=fff&size=128`;
            }

            // Basic Info
            document.getElementById('doctorName').textContent = doctor.full_name;
            const specialtyEl = document.getElementById('doctorSpecialty');
            specialtyEl.textContent = doctor.specialty;
            // Apply color to specialty text
            if (currentModalColor && currentModalColor.text) {
                specialtyEl.className = `${currentModalColor.text} font-semibold mt-1`;
            } else {
                specialtyEl.className = 'text-sky-600 font-semibold mt-1';
            }
            document.getElementById('doctorQualification').textContent = doctor.qualification;

            // Status Badge
            const statusEl = document.getElementById('doctorStatus');
            if (doctor.status === 'active') {
                statusEl.textContent = 'Active';
                statusEl.className = 'px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700';
            } else {
                statusEl.textContent = 'Inactive';
                statusEl.className = 'px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700';
            }

            // Availability Badge
            const availEl = document.getElementById('doctorAvailability');
            if (doctor.available_for_booking) {
                availEl.textContent = 'Available for Booking';
                availEl.className = 'px-3 py-1 text-xs font-medium rounded-full bg-sky-100 text-sky-700';
            } else {
                availEl.textContent = 'Not Available';
                availEl.className = 'px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700';
            }

            // Statistics - Apply dynamic color to total appointments
            const totalAppEl = document.getElementById('totalAppointments');
            totalAppEl.textContent = stats.total_appointments;

            // Apply color to total appointments stat
            let statTextColor = 'text-sky-600';
            if (currentModalColor) {
                if (currentModalColor.gradient.includes('slate')) {
                    statTextColor = 'text-slate-700';
                } else if (currentModalColor.gradient.includes('blue')) {
                    statTextColor = 'text-blue-900';
                } else if (currentModalColor.gradient.includes('teal')) {
                    statTextColor = 'text-teal-800';
                } else if (currentModalColor.gradient.includes('gray')) {
                    statTextColor = 'text-gray-700';
                } else if (currentModalColor.gradient.includes('indigo')) {
                    statTextColor = 'text-indigo-900';
                }
            }
            totalAppEl.className = `text-2xl font-bold ${statTextColor}`;

            document.getElementById('completedAppointments').textContent = stats.completed_appointments;
            document.getElementById('upcomingAppointments').textContent = stats.upcoming_appointments;

            // Personal Info
            document.getElementById('doctorEmail').textContent = doctor.email;
            document.getElementById('doctorUsername').textContent = doctor.username;
            document.getElementById('doctorPhone').textContent = doctor.phone || 'N/A';
            document.getElementById('doctorGender').textContent = doctor.gender;
            document.getElementById('doctorDob').textContent = doctor.date_of_birth;
            document.getElementById('doctorAddress').textContent = doctor.address;

            // Professional Info
            document.getElementById('doctorExperience').textContent = doctor.experience_years + ' years';
            document.getElementById('doctorLicense').textContent = doctor.license_number;
            document.getElementById('doctorFee').textContent = '₹' + doctor.consultation_fee;
            document.getElementById('doctorJoined').textContent = doctor.created_at;

            // Bio
            document.getElementById('doctorBio').textContent = doctor.bio;

            // Schedules
            const schedulesContainer = document.getElementById('doctorSchedules');
            const noSchedules = document.getElementById('noSchedules');
            schedulesContainer.innerHTML = '';

            // Determine schedule card colors based on current modal color
            let scheduleCardBg = 'bg-sky-50';
            let scheduleCardBorder = 'border-sky-100';
            let scheduleCardText = 'text-sky-700';

            if (currentModalColor) {
                if (currentModalColor.gradient.includes('slate')) {
                    scheduleCardBg = 'bg-slate-50';
                    scheduleCardBorder = 'border-slate-100';
                    scheduleCardText = 'text-slate-700';
                } else if (currentModalColor.gradient.includes('blue')) {
                    scheduleCardBg = 'bg-blue-50';
                    scheduleCardBorder = 'border-blue-100';
                    scheduleCardText = 'text-blue-900';
                } else if (currentModalColor.gradient.includes('teal')) {
                    scheduleCardBg = 'bg-teal-50';
                    scheduleCardBorder = 'border-teal-100';
                    scheduleCardText = 'text-teal-800';
                } else if (currentModalColor.gradient.includes('gray')) {
                    scheduleCardBg = 'bg-gray-50';
                    scheduleCardBorder = 'border-gray-100';
                    scheduleCardText = 'text-gray-700';
                } else if (currentModalColor.gradient.includes('indigo')) {
                    scheduleCardBg = 'bg-indigo-50';
                    scheduleCardBorder = 'border-indigo-100';
                    scheduleCardText = 'text-indigo-900';
                }
            }

            if (schedules && schedules.length > 0) {
                noSchedules.classList.add('hidden');
                schedules.forEach(schedule => {
                    const scheduleCard = document.createElement('div');
                    scheduleCard.className = `${scheduleCardBg} rounded-lg p-3 border ${scheduleCardBorder}`;
                    scheduleCard.innerHTML = `
                        <div class="font-semibold ${scheduleCardText} text-sm">${schedule.day}</div>
                        <div class="text-gray-600 text-xs mt-1">${schedule.start_time} - ${schedule.end_time}</div>
                        <div class="text-gray-500 text-xs">${schedule.slot_duration} min slots</div>
                    `;
                    schedulesContainer.appendChild(scheduleCard);
                });
            } else {
                noSchedules.classList.remove('hidden');
            }

            // Edit Button - apply color
            const editBtn = document.getElementById('editDoctorBtn');
            editBtn.href = `/admin/doctors/${doctor.id}/edit`;

            if (currentModalColor && currentModalColor.accent && currentModalColor.hover) {
                editBtn.className =
                    `px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors ${currentModalColor.accent} ${currentModalColor.hover}`;
            } else {
                editBtn.className =
                    'px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors bg-sky-600 hover:bg-sky-700';
            }
        }

        function closeDoctorViewModal() {
            const modal = document.getElementById('doctorViewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDoctorViewModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('doctorViewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDoctorViewModal();
            }
        });

        // Initial attachment
        document.addEventListener('DOMContentLoaded', attachDeleteHandlers);

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchDoctors(), 500);
        });

        document.getElementById('specialtyFilter').addEventListener('change', fetchDoctors);
        document.getElementById('statusFilter').addEventListener('change', fetchDoctors);

        // CSV Import Modal Functions with Field Mapping
        let csvHeaders = [];
        let formFields = {};
        let columnMapping = {};

        function openImportModal() {
            const modal = document.getElementById('importCSVModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            resetImportModal();
        }

        function closeImportModal() {
            const modal = document.getElementById('importCSVModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            resetImportModal();
        }

        function resetImportModal() {
            // Reset to step 1
            document.getElementById('importStep1').classList.remove('hidden');
            document.getElementById('importStep2').classList.add('hidden');
            document.getElementById('importStep3').classList.add('hidden');

            document.getElementById('step1Footer').classList.remove('hidden');
            document.getElementById('step2Footer').classList.add('hidden');
            document.getElementById('step3Footer').classList.add('hidden');

            document.getElementById('modalStepTitle').textContent = 'Import Doctors from CSV';

            // Reset form
            document.getElementById('importCSVForm').reset();
            document.getElementById('importProgress').classList.add('hidden');
            document.getElementById('importResultsDiv').classList.add('hidden');

            // Reset data
            csvHeaders = [];
            formFields = {};
            columnMapping = {};
        }

        function proceedToFieldMapping() {
            const fileInput = document.getElementById('csvFileInput');
            if (!fileInput.files || !fileInput.files[0]) {
                showNotification('Please select a CSV file', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('csv_file', fileInput.files[0]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            // Show loading state
            const nextBtn = document.querySelector('#step1Footer button:last-child');
            nextBtn.disabled = true;
            nextBtn.textContent = 'Loading...';

            fetch('{{ route('admin.doctors.csv-headers') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    nextBtn.disabled = false;
                    nextBtn.textContent = 'Next';

                    if (!data.success) {
                        showNotification(data.message || 'Failed to read CSV headers', 'error');
                        return;
                    }

                    csvHeaders = data.csv_headers || [];
                    formFields = data.form_fields || {};

                    if (csvHeaders.length === 0) {
                        showNotification('CSV file has no headers', 'error');
                        return;
                    }

                    // Populate field mapping UI
                    buildFieldMappingUI();

                    // Switch to step 2
                    document.getElementById('importStep1').classList.add('hidden');
                    document.getElementById('importStep2').classList.remove('hidden');

                    document.getElementById('step1Footer').classList.add('hidden');
                    document.getElementById('step2Footer').classList.remove('hidden');

                    document.getElementById('modalStepTitle').textContent = 'Step 2: Map CSV Fields';
                })
                .catch(error => {
                    nextBtn.disabled = false;
                    nextBtn.textContent = 'Next';
                    console.error('Error:', error);
                    showNotification('Error reading CSV file: ' + error.message, 'error');
                });
        }

        function buildFieldMappingUI() {
            const container = document.getElementById('fieldMappingContainer');
            container.innerHTML = '';

            const requiredFields = ['first_name', 'last_name', 'email', 'phone'];

            Object.entries(formFields).forEach(([fieldKey, fieldLabel]) => {
                const isRequired = requiredFields.includes(fieldKey);
                const row = document.createElement('div');
                row.className = 'flex gap-2 items-end';

                const label = document.createElement('label');
                label.className = 'text-sm font-medium text-gray-700 w-40 flex-shrink-0';
                label.innerHTML = fieldLabel + (isRequired ? '<span class="text-red-500 ml-1">*</span>' : '');

                const select = document.createElement('select');
                select.className =
                    'flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm';
                select.dataset.field = fieldKey;

                // Add default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Select CSV column --';
                select.appendChild(defaultOption);

                // Add CSV header options - try to auto-select matching ones
                csvHeaders.forEach(header => {
                    const option = document.createElement('option');
                    option.value = header;
                    option.textContent = header;
                    select.appendChild(option);

                    // Auto-match if header matches field label
                    if (header.toLowerCase() === fieldLabel.toLowerCase() ||
                        header.toLowerCase().includes(fieldLabel.toLowerCase().split(' ')[0])) {
                        option.selected = true;
                        columnMapping[header] = fieldKey; // Add to mapping
                    }
                });

                // Update mapping when selection changes
                select.addEventListener('change', (e) => {
                    const selectedHeader = e.target.value;
                    const fieldKey = e.target.dataset.field;

                    if (selectedHeader) {
                        columnMapping[selectedHeader] = fieldKey;
                    } else {
                        // Remove mapping for this field
                        Object.keys(columnMapping).forEach(key => {
                            if (columnMapping[key] === fieldKey) {
                                delete columnMapping[key];
                            }
                        });
                    }
                });

                row.appendChild(label);
                row.appendChild(select);
                container.appendChild(row);
            });
        }

        function backToFileUpload() {
            document.getElementById('importStep1').classList.remove('hidden');
            document.getElementById('importStep2').classList.add('hidden');

            document.getElementById('step1Footer').classList.remove('hidden');
            document.getElementById('step2Footer').classList.add('hidden');

            document.getElementById('modalStepTitle').textContent = 'Import Doctors from CSV';
        }

        function submitWithMapping() {
            const fileInput = document.getElementById('csvFileInput');
            if (!fileInput.files || !fileInput.files[0]) {
                showNotification('Please select a CSV file', 'error');
                return;
            }

            // Validate that at least required fields are mapped
            const requiredFields = ['first_name', 'last_name', 'email', 'phone'];
            const mappedFields = Object.values(columnMapping);
            const missingRequired = requiredFields.filter(f => !mappedFields.includes(f));

            if (missingRequired.length > 0) {
                showNotification('Please map all required fields: ' + missingRequired.join(', '), 'error');
                return;
            }

            // Prepare form data
            const formData = new FormData();
            formData.append('csv_file', fileInput.files[0]);
            formData.append('column_mapping', JSON.stringify(columnMapping));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            // Switch to step 3
            document.getElementById('importStep2').classList.add('hidden');
            document.getElementById('importStep3').classList.remove('hidden');

            document.getElementById('step2Footer').classList.add('hidden');
            document.getElementById('step3Footer').classList.add('hidden'); // Initially hidden

            document.getElementById('modalStepTitle').textContent = 'Step 3: Importing...';

            // Show and initialize progress
            const progressDiv = document.getElementById('importProgress');
            const progressBar = document.getElementById('importProgressBar');
            const progressText = document.getElementById('importProgressText');
            const resultsDiv = document.getElementById('importResultsDiv');
            const resultsContent = document.getElementById('importResultsContent');

            progressDiv.classList.remove('hidden');
            resultsDiv.classList.add('hidden');
            progressBar.style.width = '30%';
            progressText.textContent = '📤 Uploading file...';

            fetch('{{ route('admin.doctors.import-csv') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    progressBar.style.width = '90%';
                    progressText.textContent = '✔️ Finalizing...';

                    if (!data.success) {
                        throw new Error(data.message || 'Import failed');
                    }

                    // Success
                    progressBar.style.width = '100%';
                    progressBar.classList.add('bg-green-600');
                    progressText.textContent = `✅ ${data.message}`;

                    // Show results
                    setTimeout(() => {
                        resultsDiv.classList.remove('hidden');
                        const hasErrors = data.data.failed > 0;
                        resultsContent.innerHTML = `
                        <div class="${hasErrors ? 'bg-yellow-50 border-l-4 border-yellow-500' : 'bg-green-50 border-l-4 border-green-500'} p-4 rounded">
                            <h3 class="${hasErrors ? 'font-semibold text-yellow-800 mb-2' : 'font-semibold text-green-800 mb-2'}">✓ Import Completed</h3>
                            <p class="${hasErrors ? 'text-sm text-yellow-700' : 'text-sm text-green-700'}"><strong>Imported:</strong> ${data.data.success} doctor(s)</p>
                            <p class="${hasErrors ? 'text-sm text-yellow-700' : 'text-sm text-green-700'}"><strong>Failed:</strong> ${data.data.failed}</p>
                            ${data.data.errors.length > 0 ? `
                                                    <div class="mt-3">
                                                        <strong class="text-red-800">Errors (showing first 10):</strong>
                                                        <div class="mt-2 space-y-1 max-h-40 overflow-y-auto">
                                                            ${data.data.errors.slice(0, 10).map(err => {
                                                                const match = err.match(/^\[([^\]]+)\]\s*(.*)/);
                                                                const field = match ? match[1] : 'General';
                                                                const message = match ? match[2] : err;
                                                                return `<div class="text-sm p-2 bg-red-100 rounded border-l-3 border-red-500 text-red-800">
                                <span class="font-semibold text-red-900">[${field}]</span> ${message}
                            </div>`;
                                                            }).join('')}
                                                            ${data.data.errors.length > 10 ? `<div class="text-sm p-2 bg-red-50 rounded text-red-700 font-semibold">... and ${data.data.errors.length - 10} more errors</div>` : ''}
                                                        </div>
                                                    </div>
                                                ` : ''}
                        </div>
                    `;

                        // Show done button
                        document.getElementById('step3Footer').classList.remove('hidden');

                        // Show notification and refresh
                        showNotification(data.message, 'success');
                        fetchDoctors(); // Refresh the doctors list

                        // Auto-close only if no errors
                        if (!hasErrors) {
                            setTimeout(() => {
                                closeImportModal();
                            }, 3000);
                        }
                    }, 800);
                })
                .catch(error => {
                    console.error('Import error:', error);
                    progressBar.style.width = '100%';
                    progressBar.classList.add('bg-red-500');
                    progressText.textContent = `❌ Error: ${error.message}`;

                    // Show error in results
                    setTimeout(() => {
                        resultsDiv.classList.remove('hidden');
                        resultsContent.innerHTML = `
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <h3 class="font-semibold text-red-800 mb-2">✗ Import Failed</h3>
                            <p class="text-sm text-red-700">${error.message}</p>
                        </div>
                    `;

                        // Show done button
                        document.getElementById('step3Footer').classList.remove('hidden');
                    }, 800);

                    showNotification(`Import error: ${error.message}`, 'error');
                });
        }
    </script>

    <!-- Import CSV Modal -->
    <div id="importCSVModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">
                    <span id="modalStepTitle">Import Doctors from CSV</span>
                </h2>
                <button onclick="closeImportModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <!-- Step 1: File Upload -->
                <div id="importStep1" class="block">
                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            Select a CSV file with doctor data. You'll be able to map CSV columns to form fields in the next
                            step.
                        </p>
                    </div>

                    <!-- File Input Form -->
                    <form id="importCSVForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                            <input type="file" id="csvFileInput" name="csv_file" accept=".csv,.txt"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Supported formats: CSV, TXT (max 5MB)</p>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Field Mapping -->
                <div id="importStep2" class="hidden">
                    <!-- Instructions -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-green-800 font-medium">Map CSV columns to form fields</p>
                        <p class="text-xs text-green-700 mt-1">Select which CSV column should map to each doctor field</p>
                    </div>

                    <!-- Field Mapping Container -->
                    <div id="fieldMappingContainer" class="space-y-3">
                        <!-- Mapping rows will be inserted here by JavaScript -->
                    </div>
                </div>

                <!-- Step 3: Progress Bar & Results -->
                <div id="importStep3" class="hidden">
                    <!-- Progress Bar -->
                    <div id="importProgress" class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Importing...</span>
                            <span id="importProgressText" class="text-sm font-medium text-gray-600">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="importProgressBar" class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Import Results -->
                    <div id="importResultsDiv" class="hidden mb-4">
                        <div id="importResultsContent"
                            class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-48 overflow-y-auto"></div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div id="step1Footer" class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeImportModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="proceedToFieldMapping()"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-colors">
                    Next
                </button>
            </div>

            <div id="step2Footer" class="hidden px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="backToFileUpload()"
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                    Back
                </button>
                <button type="button" onclick="submitWithMapping()"
                    class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg font-medium transition-colors">
                    Import
                </button>
            </div>

            <div id="step3Footer" class="hidden px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeImportModal()"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-colors">
                    Done
                </button>
            </div>
        </div>
    </div>
@endsection
