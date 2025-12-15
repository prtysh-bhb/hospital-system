<html>

<head>
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding: 20px;
            background-color: #f9fafb;
        }
      
    </style>
</head>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 animate-fade-in">
    <div class="mb-4 sm:mb-0">
        <h4 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 mb-2">
            Welcome, {{ auth()->user()->full_name }} 👋
        </h4>
        <p class="text-gray-600 text-sm sm:text-base">
            Manage your appointments and medical schedule from your dashboard.
        </p>
    </div>

    <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 animate-scale-in hover:scale-105">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Logout
    </button>
</div>

<form id="logout-form" action="{{ route('patient.logout') }}" method="POST" class="hidden">
    @csrf
</form>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 animate-slide-up">
    <div
        class="bg-gradient-to-r from-blue-50 to-white rounded-xl shadow-sm p-5 border border-blue-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-xs font-medium text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats->total }}</p>
            </div>
        </div>
    </div>

    <div
        class="bg-gradient-to-r from-sky-50 to-white rounded-xl shadow-sm p-5 border border-sky-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-sky-100">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-xs font-medium text-gray-500">Upcoming</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats->upcoming }}</p>
            </div>
        </div>
    </div>

    <div
        class="bg-gradient-to-r from-green-50 to-white rounded-xl shadow-sm p-5 border border-green-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-xs font-medium text-gray-500">Today</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats->today }}</p>
            </div>
        </div>
    </div>

    <div
        class="bg-gradient-to-r from-emerald-50 to-white rounded-xl shadow-sm p-5 border border-emerald-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-emerald-100">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-xs font-medium text-gray-500">Completed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats->completed }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-sky-50 to-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Your Appointments</h3>
                <p class="text-sm text-gray-600 mt-1">View and manage all your medical appointments</p>
            </div>
            <a id="new-appointment-btn" href="{{ route('booking') }}"
                class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-sky-600 text-white font-medium rounded-lg hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-all duration-200 text-sm animate-pulse">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Appointment
            </a>
        </div>
    </div>

    @if ($appointments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
            @foreach ($appointments as $index => $appointment)
                <div class="appointment-card bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition duration-200 cursor-pointer"
                    data-index="{{ $index }}">
                    <div class="p-5">
                        <div class="flex items-center mb-4">
                            <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>

                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-gray-800">
                                    {{ $appointment->doctor_name }}
                                </h4>
                                <p class="text-xs text-sky-600 font-medium">
                                    {{ $appointment->specialty }}
                                </p>
                                @if ($appointment->qualification)
                                    <p class="text-xs text-gray-400">{{ $appointment->qualification }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">
                                {{ $appointment->date->format('D, M j, Y') }}
                            </span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $appointment->time->format('g:i A') }}</span>
                            <span class="mx-2 text-gray-300">•</span>
                            <span class="text-gray-500">{{ $appointment->duration }} mins</span>
                        </div>

                        @if ($appointment->appointment_type)
                            <div class="mb-3">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ ucwords(str_replace('_', ' ', $appointment->appointment_type)) }}
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'no_show' => 'bg-gray-100 text-gray-700',
                                ];
                                $dotColors = [
                                    'completed' => 'bg-green-500',
                                    'confirmed' => 'bg-blue-500',
                                    'pending' => 'bg-yellow-500',
                                    'cancelled' => 'bg-red-500',
                                    'no_show' => 'bg-gray-500',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotColors[$appointment->status] ?? 'bg-gray-500' }}"></span>
                                {{ ucwords(str_replace('_', ' ', $appointment->status)) }}
                            </span>

                            <div class="flex items-center space-x-2">
                                @if ($appointment->has_prescription)
                                    <span class="text-green-600" title="Prescription Available">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </span>
                                @endif
                                <button
                                    class="view-appointment-details text-sm font-medium text-sky-600 hover:text-sky-800 transition">
                                    View Details →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No appointments yet</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by booking your first appointment.</p>
            <a href="{{ route('booking') }}"
                class="mt-4 inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">
                Book Appointment
            </a>
        </div>
    @endif
</div>

<div id="medical-history-section"
    class="hidden mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Medical History</h3>
                <p class="text-sm text-gray-600 mt-1">View your past appointments and treatment records</p>
            </div>
            <button id="close-history-btn"
                class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Close History
            </button>
        </div>
    </div>

    <div id="medical-history-content" class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date & Time
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Doctor
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Specialty
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="history-table-body" class="bg-white divide-y divide-gray-200">
                    <!-- History will be populated here by JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="no-history-message" class="text-center py-12 hidden">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No past appointments found</h3>
            <p class="mt-1 text-sm text-gray-500">You don't have any past appointments in your history.</p>
        </div>
    </div>
</div>

<div id="appointment-details-modal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3 p-6 relative max-h-[90vh] overflow-y-auto">
        <button id="close-appointment-details"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Appointment Details</h3>
        <div id="appointment-details-content">
            <!-- Appointment details will be populated here by JavaScript -->
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-slide-up" style="animation-delay: 0.3s">
    <div class="bg-gradient-to-r from-sky-50 to-blue-50 rounded-xl shadow-sm p-6 border border-sky-100">
        <div class="flex items-start">
            <div class="p-3 rounded-lg bg-white shadow-sm">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Need to see a doctor?</h3>
                <p class="text-gray-600 text-sm mb-4">Schedule a new appointment with any of our specialists</p>
                <a href="{{ route('booking') }}"
                    class="inline-flex items-center px-4 py-2 bg-white text-sky-600 font-medium rounded-lg border border-sky-200 hover:bg-sky-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-all duration-200 text-sm">
                    Book Appointment
                </a>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-start">
            <div class="p-3 rounded-lg bg-white shadow-sm">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Medical History</h3>
                <p class="text-gray-600 text-sm mb-4">Access your past appointments and treatment records</p>
                <button id="medical-history-button"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 text-sm">
                    View History
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const appointments = @json($appointments);

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const statusColors = {
            'completed': 'bg-green-100 text-green-700',
            'confirmed': 'bg-blue-100 text-blue-700',
            'pending': 'bg-yellow-100 text-yellow-700',
            'cancelled': 'bg-red-100 text-red-700',
            'no_show': 'bg-gray-100 text-gray-700'
        };

        const dotColors = {
            'completed': 'bg-green-500',
            'confirmed': 'bg-blue-500',
            'pending': 'bg-yellow-500',
            'cancelled': 'bg-red-500',
            'no_show': 'bg-gray-500'
        };

        // Function to filter past appointments
        function getPastAppointments() {
            return appointments.filter(appointment => {
                const appointmentDate = new Date(appointment.date);
                appointmentDate.setHours(0, 0, 0, 0);
                return appointmentDate < today;
            });
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function generatePrescriptionHTML(prescription) {
            if (!prescription) return '';

            let medicationsHTML = '';
            if (prescription.medications && prescription.medications.length > 0) {
                medicationsHTML = prescription.medications.map(med => `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="font-medium text-gray-800">${med.name || 'N/A'}</p>
                        <p class="text-sm text-gray-600">${med.dosage || ''} ${med.frequency || ''}</p>
                        ${med.duration ? `<p class="text-xs text-gray-500">Duration: ${med.duration}</p>` : ''}
                    </div>
                `).join('');
            } else {
                medicationsHTML = '<p class="text-gray-500 text-sm">No medications recorded</p>';
            }

            return `
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Prescription
                        <span class="ml-2 text-xs font-normal text-gray-500">#${prescription.prescription_number || 'N/A'}</span>
                    </h4>
                    
                    ${prescription.diagnosis ? `
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600">Diagnosis</p>
                        <p class="text-gray-800">${prescription.diagnosis}</p>
                    </div>
                    ` : ''}
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">Medications</p>
                        <div class="space-y-2">
                            ${medicationsHTML}
                        </div>
                    </div>
                    
                    ${prescription.instructions ? `
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600">Instructions</p>
                        <p class="text-gray-800 text-sm">${prescription.instructions}</p>
                    </div>
                    ` : ''}
                    
                    ${prescription.follow_up_date ? `
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm font-medium text-blue-800">Follow-up Date</p>
                        <p class="text-blue-700">${prescription.follow_up_date}</p>
                    </div>
                    ` : ''}
                    
                    ${prescription.notes ? `
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600">Additional Notes</p>
                        <p class="text-gray-800 text-sm">${prescription.notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        function generateAppointmentDetailsHTML(apt) {
            const prescriptionHTML = apt.has_prescription && apt.prescription ?
                generatePrescriptionHTML(apt.prescription) :
                '';

            return `
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">${apt.doctor_name}</h4>
                            <p class="text-sm text-sky-600 font-medium">${apt.specialty}</p>
                            ${apt.qualification ? `<p class="text-xs text-gray-500">${apt.qualification}</p>` : ''}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Appointment #</p>
                            <p class="text-sm font-medium text-gray-800">${apt.appointment_number || 'N/A'}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${statusColors[apt.status] || 'bg-gray-100 text-gray-700'}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${dotColors[apt.status] || 'bg-gray-500'}"></span>
                                ${apt.status ? apt.status.charAt(0).toUpperCase() + apt.status.slice(1).replace('_', ' ') : 'N/A'}
                            </span>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Date</p>
                            <p class="text-sm font-medium text-gray-800">${formatDate(apt.date)}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Time</p>
                            <p class="text-sm font-medium text-gray-800">${apt.time || 'N/A'}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Duration</p>
                            <p class="text-sm font-medium text-gray-800">${apt.duration || 30} minutes</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Consultation Fee</p>
                            <p class="text-sm font-medium text-gray-800">₹${apt.consultation_fee || 'N/A'}</p>
                        </div>
                    </div>
                    
                    ${apt.appointment_type ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Appointment Type</p>
                        <p class="text-sm font-medium text-gray-800">${apt.appointment_type.replace('_', ' ').replace(/\\b\\w/g, l => l.toUpperCase())}</p>
                    </div>
                    ` : ''}
                    
                    ${apt.reason_for_visit ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Reason for Visit</p>
                        <p class="text-sm text-gray-800">${apt.reason_for_visit}</p>
                    </div>
                    ` : ''}
                    
                    ${apt.symptoms ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Symptoms</p>
                        <p class="text-sm text-gray-800">${apt.symptoms}</p>
                    </div>
                    ` : ''}
                    
                    ${apt.notes ? `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Notes</p>
                        <p class="text-sm text-gray-800">${apt.notes}</p>
                    </div>
                    ` : ''}
                    
                    ${apt.cancellation_reason ? `
                    <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                        <p class="text-xs text-red-600 mb-1">Cancellation Reason</p>
                        <p class="text-sm text-red-800">${apt.cancellation_reason}</p>
                    </div>
                    ` : ''}
                    
                    ${prescriptionHTML}
                    
                    <div class="pt-4 border-t border-gray-100">
                        ${apt.has_prescription ? `
                        <button onclick="downloadPrescription('${apt.id}')" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-200 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Prescription
                        </button>
                        ` : ''}
                        ${apt.status === 'pending' || apt.status === 'confirmed' ? `
                        <button class="w-full bg-sky-600 text-white py-2 rounded-lg hover:bg-sky-700 transition duration-200 mb-2">
                            Reschedule Appointment
                        </button>
                        <button class="w-full border border-red-300 text-red-600 py-2 rounded-lg hover:bg-red-50 transition duration-200">
                            Cancel Appointment
                        </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        function populateHistoryTable() {
            const historyTableBody = document.getElementById('history-table-body');
            const noHistoryMessage = document.getElementById('no-history-message');
            const pastAppointments = getPastAppointments();

            if (pastAppointments.length === 0) {
                historyTableBody.innerHTML = '';
                noHistoryMessage.classList.remove('hidden');
                return;
            }

            noHistoryMessage.classList.add('hidden');

            pastAppointments.sort((a, b) => new Date(b.date) - new Date(a.date));

            let tableHTML = '';

            pastAppointments.forEach(appointment => {
                const appointmentDate = new Date(appointment.date);
                const formattedDate = appointmentDate.toLocaleDateString('en-US', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                const statusClass = statusColors[appointment.status] || 'bg-gray-100 text-gray-700';

                tableHTML += `
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">${formattedDate}</div>
                            <div class="text-sm text-gray-500">${appointment.time}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${appointment.doctor_name}</div>
                                    <div class="text-sm text-gray-500">${appointment.qualification || ''}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${appointment.specialty}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1).replace('_', ' ')}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewHistoryAppointmentDetails('${appointment.id}')" class="text-sky-600 hover:text-sky-900 mr-3">
                                View Details
                            </button>
                            ${appointment.has_prescription ? `
                            <span class="text-green-600 ml-2" title="Has Prescription">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            ` : ''}
                        </td>
                    </tr>
                `;
            });

            historyTableBody.innerHTML = tableHTML;
        }

        const medicalHistoryButton = document.getElementById('medical-history-button');
        const medicalHistorySection = document.getElementById('medical-history-section');
        const closeHistoryBtn = document.getElementById('close-history-btn');

        medicalHistoryButton.addEventListener('click', function() {
            populateHistoryTable();
            medicalHistorySection.classList.remove('hidden');
            medicalHistorySection.scrollIntoView({
                behavior: 'smooth'
            });
        });

        closeHistoryBtn.addEventListener('click', function() {
            medicalHistorySection.classList.add('hidden');
        });

        const viewButtons = document.querySelectorAll('.view-appointment-details');
        viewButtons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const appointmentCard = this.closest('.appointment-card');
                const cardIndex = appointmentCard.dataset.index;
                const appointment = appointments[cardIndex];

                if (!appointment) return;

                const modal = document.getElementById('appointment-details-modal');
                const content = document.getElementById('appointment-details-content');

                content.innerHTML = generateAppointmentDetailsHTML(appointment);
                modal.classList.remove('hidden');
            });
        });
        document.querySelectorAll('.appointment-card').forEach((card, index) => {
            card.addEventListener('click', function(e) {
                if (e.target.closest('.view-appointment-details')) return;

                const cardIndex = this.dataset.index;
                const appointment = appointments[cardIndex];

                if (!appointment) return;

                const modal = document.getElementById('appointment-details-modal');
                const content = document.getElementById('appointment-details-content');

                content.innerHTML = generateAppointmentDetailsHTML(appointment);
                modal.classList.remove('hidden');
            });
        });

        // Close appointment details modal
        document.getElementById('close-appointment-details').addEventListener('click', function() {
            document.getElementById('appointment-details-modal').classList.add('hidden');
        });

        document.getElementById('appointment-details-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                this.classList.add('hidden');
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('appointment-details-modal').classList.add('hidden');
            }
        });

        window.viewHistoryAppointmentDetails = function(appointmentId) {
            // Find the appointment by ID
            const appointment = appointments.find(appt => appt.id == appointmentId);
            if (!appointment) return;

            const modal = document.getElementById('appointment-details-modal');
            const content = document.getElementById('appointment-details-content');

            content.innerHTML = generateAppointmentDetailsHTML(appointment);
            modal.classList.remove('hidden');
        };

        window.downloadPrescription = function(appointmentId) {
            // In a real application, this would trigger a file download
            window.open(`/patient/prescription/${appointmentId}/download`, '_blank');
        };
    });
</script>

</html>
