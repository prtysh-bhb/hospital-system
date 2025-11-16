@extends('layouts.doctor')

@section('title', 'Appointment Details')

@section('page-title', 'Appointment Details')

@section('header-back-button')
<a href="{{ route('doctor.appointments') }}" class="text-gray-600 hover:text-gray-800">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Appointment & Patient Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Appointment Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Appointment Information</h3>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                    Scheduled
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Appointment ID</p>
                    <p class="text-base font-medium text-gray-800">APT-2025-001234</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Date & Time</p>
                    <p class="text-base font-medium text-gray-800">November 16, 2025 • 09:00 AM</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Type</p>
                    <p class="text-base font-medium text-gray-800">Consultation</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Consultation Fee</p>
                    <p class="text-base font-medium text-gray-800">$150.00</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Reason for Visit</p>
                    <p class="text-base font-medium text-gray-800">Chest pain and irregular heartbeat</p>
                </div>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Patient Information</h3>

            <div class="flex items-start space-x-4 mb-6">
                <img src="https://ui-avatars.com/api/?name=John+Smith&background=10b981&color=fff"
                     class="w-20 h-20 rounded-full" alt="Patient">
                <div class="flex-1">
                    <h4 class="text-xl font-semibold text-gray-800">John Smith</h4>
                    <p class="text-sm text-gray-500 mb-2">Patient ID: PT-2024-001</p>
                    <div class="flex space-x-4 text-sm">
                        <span class="text-gray-600">Age: 45</span>
                        <span class="text-gray-400">•</span>
                        <span class="text-gray-600">Male</span>
                        <span class="text-gray-400">•</span>
                        <span class="text-gray-600">Blood Type: O+</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="text-base font-medium text-gray-800">john.smith@email.com</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Phone</p>
                    <p class="text-base font-medium text-gray-800">+1 (555) 123-4567</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Address</p>
                    <p class="text-base font-medium text-gray-800">123 Main Street, New York, NY 10001</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Date of Birth</p>
                    <p class="text-base font-medium text-gray-800">January 15, 1980</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Emergency Contact</p>
                    <p class="text-base font-medium text-gray-800">Jane Smith: +1 (555) 987-6543</p>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Medical History</h3>

            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Allergies</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full">Penicillin</span>
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full">Peanuts</span>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Chronic Conditions</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm rounded-full">Hypertension</span>
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm rounded-full">Type 2 Diabetes</span>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Current Medications</p>
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                        <li>Metformin 500mg - Twice daily</li>
                        <li>Lisinopril 10mg - Once daily</li>
                        <li>Aspirin 81mg - Once daily</li>
                    </ul>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Previous Visits</p>
                    <div class="space-y-2">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-800">August 10, 2025 - Annual Checkup</p>
                            <p class="text-xs text-gray-600 mt-1">Dr. Sarah Johnson - Cardiologist</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-800">March 22, 2025 - Hypertension Follow-up</p>
                            <p class="text-xs text-gray-600 mt-1">Dr. Sarah Johnson - Cardiologist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultation Notes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Consultation Notes</h3>
            <textarea
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                rows="6"
                placeholder="Enter consultation notes, diagnosis, and treatment plan...">Chief Complaint: Patient reports chest pain and irregular heartbeat for the past 2 days.

Examination: Blood pressure 145/92, Heart rate irregular at 88 bpm

Diagnosis: Suspected Atrial Fibrillation

Plan:
- Order ECG and Holter monitor
- Adjust blood pressure medication
- Schedule follow-up in 2 weeks</textarea>
        </div>

        <!-- Prescription -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Prescription</h3>
                <button class="px-4 py-2 bg-sky-600 text-white text-sm rounded-lg hover:bg-sky-700">
                    + Add Medication
                </button>
            </div>

            <div class="space-y-3">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-800">Metoprolol 25mg</p>
                            <p class="text-sm text-gray-600 mt-1">Take one tablet twice daily with food</p>
                            <p class="text-xs text-gray-500 mt-1">Duration: 30 days • Quantity: 60 tablets</p>
                        </div>
                        <button class="text-red-600 hover:text-red-700 text-sm">Remove</button>
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-800">Eliquis 5mg</p>
                            <p class="text-sm text-gray-600 mt-1">Take one tablet twice daily</p>
                            <p class="text-xs text-gray-500 mt-1">Duration: 30 days • Quantity: 60 tablets</p>
                        </div>
                        <button class="text-red-600 hover:text-red-700 text-sm">Remove</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-4">
            <button class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                Mark as Completed
            </button>
            <button class="flex-1 px-6 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 font-medium">
                Save Notes
            </button>
            <button class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                Cancel
            </button>
        </div>
    </div>

    <!-- Right Column - Quick Actions -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button class="w-full px-4 py-3 bg-sky-50 text-sky-700 rounded-lg hover:bg-sky-100 text-sm font-medium text-left">
                    View Patient History
                </button>
                <button class="w-full px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 text-sm font-medium text-left">
                    View Test Results
                </button>
                <button class="w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 text-sm font-medium text-left">
                    Generate Report
                </button>
                <button class="w-full px-4 py-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 text-sm font-medium text-left">
                    Reschedule Appointment
                </button>
                <button class="w-full px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-sm font-medium text-left">
                    Cancel Appointment
                </button>
            </div>
        </div>

        <!-- Vital Signs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vital Signs</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-sm text-gray-600">Blood Pressure</span>
                    <input type="text" placeholder="120/80"
                           class="w-24 px-2 py-1 text-sm border border-gray-300 rounded text-right">
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-sm text-gray-600">Heart Rate</span>
                    <input type="text" placeholder="72"
                           class="w-24 px-2 py-1 text-sm border border-gray-300 rounded text-right">
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-sm text-gray-600">Temperature (°F)</span>
                    <input type="text" placeholder="98.6"
                           class="w-24 px-2 py-1 text-sm border border-gray-300 rounded text-right">
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-sm text-gray-600">Oxygen Sat (%)</span>
                    <input type="text" placeholder="98"
                           class="w-24 px-2 py-1 text-sm border border-gray-300 rounded text-right">
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Weight (lbs)</span>
                    <input type="text" placeholder="180"
                           class="w-24 px-2 py-1 text-sm border border-gray-300 rounded text-right">
                </div>
            </div>
            <button class="w-full mt-4 px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm">
                Save Vitals
            </button>
        </div>

        <!-- Lab Tests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Lab Tests</h3>
            <div class="space-y-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-sm text-gray-700">ECG</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-sm text-gray-700">Complete Blood Count</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-sm text-gray-700">Lipid Panel</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-sm text-gray-700">Thyroid Function</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="w-4 h-4 text-sky-600 rounded">
                    <span class="text-sm text-gray-700">Echocardiogram</span>
                </label>
            </div>
            <button class="w-full mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                Order Tests
            </button>
        </div>

        <!-- Follow-up -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Schedule Follow-up</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                    <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <button class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm">
                    Schedule Follow-up
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
