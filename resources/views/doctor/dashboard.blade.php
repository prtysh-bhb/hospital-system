@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <p class="text-xl sm:text-2xl font-bold text-gray-800">12</p>
        <p class="text-xs sm:text-sm text-gray-500">Today's Appointments</p>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-xl sm:text-2xl font-bold text-gray-800">8</p>
        <p class="text-xs sm:text-sm text-gray-500">Completed</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
    <h3 class="font-semibold text-gray-800 mb-3 sm:mb-4 text-base sm:text-lg">Quick Actions</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <button class="px-3 sm:px-4 py-2 sm:py-3 bg-sky-50 text-sky-700 rounded-lg font-medium hover:bg-sky-100 text-sm sm:text-base">
            View Schedule
        </button>
        <button class="px-3 sm:px-4 py-2 sm:py-3 bg-purple-50 text-purple-700 rounded-lg font-medium hover:bg-purple-100 text-sm sm:text-base">
            Manage Availability
        </button>
    </div>
</div>

<!-- Today's Appointments -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-4 sm:p-6 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-base sm:text-lg">Today's Appointments</h3>
    </div>
    <div class="divide-y divide-gray-100">
        <div class="p-4 sm:p-6">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Rahul Patel</p>
                    <p class="text-xs sm:text-sm text-gray-500">35 yrs • Male • A+</p>
                </div>
                <span class="px-2 sm:px-3 py-1 text-xs font-medium text-sky-700 bg-sky-100 rounded-full">10:30 AM</span>
            </div>
            <p class="text-xs sm:text-sm text-gray-600 mb-3">Chief Complaint: Chest pain</p>
            <button class="w-full px-3 sm:px-4 py-2 bg-sky-600 text-white rounded-lg font-medium text-sm sm:text-base">Start Consultation</button>
        </div>

        <div class="p-4 sm:p-6">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Sita Kumari</p>
                    <p class="text-xs sm:text-sm text-gray-500">28 yrs • Female • B+</p>
                </div>
                <span class="px-2 sm:px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">11:00 AM</span>
            </div>
            <p class="text-xs sm:text-sm text-gray-600 mb-3">Chief Complaint: Follow-up checkup</p>
            <button class="w-full px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium text-sm sm:text-base">View Details</button>
        </div>
    </div>
</div>
@endsection
