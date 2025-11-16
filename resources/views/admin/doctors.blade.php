@extends('layouts.admin')

@section('title', 'Doctors Management')

@section('page-title', 'Doctors Management')

@section('header-actions')
<a href="{{ route('admin.doctor-add') }}" class="px-4 sm:px-6 py-2 text-sm sm:text-base text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">+ Add Doctor</a>
@endsection

@section('content')
<!-- Search & Filter -->
<div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="md:col-span-2">
            <input type="text" placeholder="Search by name, specialty, ID..." class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
        </div>
        <div>
            <select class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option>All Specialties</option>
                <option>Cardiologist</option>
                <option>Pediatrician</option>
                <option>Orthopedic</option>
                <option>Dermatologist</option>
            </select>
        </div>
        <div>
            <select class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option>All Status</option>
                <option>Active</option>
                <option>On Leave</option>
                <option>Inactive</option>
            </select>
        </div>
    </div>
</div>

<!-- Doctors Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
    <!-- Doctor Card 1 -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="h-24 sm:h-32 bg-gradient-to-r from-sky-500 to-sky-600"></div>
        <div class="p-4 sm:p-6 -mt-12 sm:-mt-16">
            <div class="flex justify-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Dr+Rajesh+Sharma&background=0ea5e9&color=fff&size=128" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg" alt="Doctor">
            </div>
            <div class="text-center">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Dr. Rajesh Sharma</h3>
                <p class="text-xs sm:text-sm text-sky-600 font-medium mb-2">Cardiologist</p>
                <p class="text-xs text-gray-500 mb-1">MBBS, MD (Cardiology)</p>
                <p class="text-xs text-gray-500 mb-4">12 years experience</p>
            </div>

            <div class="space-y-2 sm:space-y-3 mb-4">
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>+91 98765 43210</span>
                </div>
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="truncate">Mon, Wed, Fri • 9AM - 5PM</span>
                </div>
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>₹800 consultation</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="px-2 sm:px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Active</span>
                <span class="text-xs sm:text-sm text-gray-600">15 patients today</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <button class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm text-sky-600 border border-sky-600 rounded-lg hover:bg-sky-50 font-medium">View Details</button>
                <button class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm text-white bg-sky-600 rounded-lg hover:bg-sky-700 font-medium">Edit</button>
            </div>
        </div>
    </div>

    <!-- Doctor Card 2 -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="h-24 sm:h-32 bg-gradient-to-r from-purple-500 to-purple-600"></div>
        <div class="p-4 sm:p-6 -mt-12 sm:-mt-16">
            <div class="flex justify-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Dr+Priya+Mehta&background=8b5cf6&color=fff&size=128" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg" alt="Doctor">
            </div>
            <div class="text-center">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Dr. Priya Mehta</h3>
                <p class="text-xs sm:text-sm text-purple-600 font-medium mb-2">Pediatrician</p>
                <p class="text-xs text-gray-500 mb-1">MBBS, MD (Pediatrics)</p>
                <p class="text-xs text-gray-500 mb-4">8 years experience</p>
            </div>

            <div class="space-y-2 sm:space-y-3 mb-4">
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>+91 98765 43211</span>
                </div>
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="truncate">Tue, Thu, Sat • 10AM - 6PM</span>
                </div>
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>₹600 consultation</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="px-2 sm:px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Active</span>
                <span class="text-xs sm:text-sm text-gray-600">10 patients today</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <button class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 font-medium">View Details</button>
                <button class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm text-white bg-purple-600 rounded-lg hover:bg-purple-700 font-medium">Edit</button>
            </div>
        </div>
    </div>

    <!-- Doctor Card 3 -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="h-24 sm:h-32 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
        <div class="p-4 sm:p-6 -mt-12 sm:-mt-16">
            <div class="flex justify-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Dr+Arun+Singh&background=10b981&color=fff&size=128" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg" alt="Doctor">
            </div>
            <div class="text-center">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Dr. Arun Singh</h3>
                <p class="text-xs sm:text-sm text-emerald-600 font-medium mb-2">Orthopedic</p>
                <p class="text-xs text-gray-500 mb-1">MBBS, MS (Orthopedics)</p>
                <p class="text-xs text-gray-500 mb-4">15 years experience</p>
            </div>

            <div class="space-y-2 sm:space-y-3 mb-4">
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>+91 98765 43212</span>
                </div>
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="truncate">Mon-Fri • 8AM - 4PM</span>
                </div>
                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>₹900 consultation</span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="px-2 sm:px-3 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">On Leave</span>
                <span class="text-xs sm:text-sm text-gray-600">Back on 20 Dec</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <button class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm text-emerald-600 border border-emerald-600 rounded-lg hover:bg-emerald-50 font-medium">View Details</button>
                <button class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 font-medium">Edit</button>
            </div>
        </div>
    </div>
</div>
@endsection
