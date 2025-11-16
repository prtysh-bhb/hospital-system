@extends('layouts.frontdesk')

@section('title', 'Doctor Schedule')

@section('page-title', 'Doctor Schedule')

@section('content')
<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Date</label>
            <input type="date" value="2025-11-16" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
        </div>
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Specialty</label>
            <select class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                <option>All Specialties</option>
                <option>Cardiology</option>
                <option>Pediatrics</option>
                <option>Orthopedic</option>
                <option>Dermatology</option>
                <option>Neurology</option>
            </select>
        </div>
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Availability</label>
            <select class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                <option>All Doctors</option>
                <option>Available</option>
                <option>Busy</option>
                <option>Unavailable</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="w-full px-4 sm:px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm sm:text-base">
                Filter
            </button>
        </div>
    </div>
</div>

<!-- Today's Schedule Overview -->
<div class="mb-4 sm:mb-6">
    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Today's Schedule - November 16, 2025</h3>
</div>

<!-- Doctor Schedule Cards -->
<div class="space-y-4 sm:space-y-6">
    <!-- Doctor 1 - Dr. Sarah Johnson -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 sm:p-6 border-b">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="https://ui-avatars.com/api/?name=Dr+Sarah+Johnson&background=0ea5e9&color=fff"
                         class="w-12 h-12 sm:w-16 sm:h-16 rounded-full" alt="Doctor">
                    <div>
                        <h4 class="text-base sm:text-xl font-semibold text-gray-800">Dr. Sarah Johnson</h4>
                        <p class="text-xs sm:text-sm text-gray-500">Cardiologist • 15 years experience</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Available</span>
                            <span class="text-xs text-gray-500">Room 201</span>
                        </div>
                    </div>
                </div>
                <div class="lg:text-right">
                    <p class="text-xs sm:text-sm text-gray-500">Working Hours</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-800">09:00 AM - 05:00 PM</p>
                    <p class="text-xs sm:text-sm text-sky-600 mt-1">4 / 8 slots available</p>
                </div>
            </div>
        </div>

        <!-- Time Slots -->
        <div class="p-3 sm:p-6 overflow-x-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-3">
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">09:00 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">09:30 AM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">10:00 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">10:30 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">11:00 AM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">11:30 AM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-gray-300 bg-gray-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">12:00 PM</p>
                    <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">Lunch Break</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-gray-300 bg-gray-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">12:30 PM</p>
                    <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">Lunch Break</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">02:00 PM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">02:30 PM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-blue-300 bg-blue-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-blue-700">03:00 PM</p>
                    <p class="text-xs text-blue-600 mt-0.5 sm:mt-1">Completed</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">03:30 PM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor 2 - Dr. Michael Smith -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 sm:p-6 border-b">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="https://ui-avatars.com/api/?name=Dr+Michael+Smith&background=10b981&color=fff"
                         class="w-12 h-12 sm:w-16 sm:h-16 rounded-full" alt="Doctor">
                    <div>
                        <h4 class="text-base sm:text-xl font-semibold text-gray-800">Dr. Michael Smith</h4>
                        <p class="text-xs sm:text-sm text-gray-500">Pediatrician • 12 years experience</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">Busy</span>
                            <span class="text-xs text-gray-500">Room 105</span>
                        </div>
                    </div>
                </div>
                <div class="lg:text-right">
                    <p class="text-xs sm:text-sm text-gray-500">Working Hours</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-800">08:00 AM - 04:00 PM</p>
                    <p class="text-xs sm:text-sm text-sky-600 mt-1">2 / 10 slots available</p>
                </div>
            </div>
        </div>

        <!-- Time Slots -->
        <div class="p-3 sm:p-6 overflow-x-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-3">
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">08:00 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">08:30 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">09:00 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">09:30 AM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">10:00 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">10:30 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">11:00 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">11:30 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-gray-300 bg-gray-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">12:00 PM</p>
                    <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">Break</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">01:00 PM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">01:30 PM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">02:00 PM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor 3 - Dr. Emily Williams -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 sm:p-6 border-b">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="https://ui-avatars.com/api/?name=Dr+Emily+Williams&background=8b5cf6&color=fff"
                         class="w-12 h-12 sm:w-16 sm:h-16 rounded-full" alt="Doctor">
                    <div>
                        <h4 class="text-base sm:text-xl font-semibold text-gray-800">Dr. Emily Williams</h4>
                        <p class="text-xs sm:text-sm text-gray-500">Orthopedic • 10 years experience</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">Unavailable</span>
                            <span class="text-xs text-gray-500">On Leave</span>
                        </div>
                    </div>
                </div>
                <div class="lg:text-right">
                    <p class="text-xs sm:text-sm text-gray-500">Status</p>
                    <p class="text-base sm:text-lg font-semibold text-red-600">On Leave</p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Returns: Nov 18, 2025</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor 4 - Dr. David Brown -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 sm:p-6 border-b">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="https://ui-avatars.com/api/?name=Dr+David+Brown&background=f59e0b&color=fff"
                         class="w-12 h-12 sm:w-16 sm:h-16 rounded-full" alt="Doctor">
                    <div>
                        <h4 class="text-base sm:text-xl font-semibold text-gray-800">Dr. David Brown</h4>
                        <p class="text-xs sm:text-sm text-gray-500">Dermatologist • 8 years experience</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Available</span>
                            <span class="text-xs text-gray-500">Room 303</span>
                        </div>
                    </div>
                </div>
                <div class="lg:text-right">
                    <p class="text-xs sm:text-sm text-gray-500">Working Hours</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-800">10:00 AM - 06:00 PM</p>
                    <p class="text-xs sm:text-sm text-sky-600 mt-1">6 / 8 slots available</p>
                </div>
            </div>
        </div>

        <!-- Time Slots -->
        <div class="p-3 sm:p-6 overflow-x-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-3">
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">10:00 AM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">11:00 AM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">12:00 PM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">02:00 PM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">03:00 PM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-red-300 bg-red-50 rounded-lg text-center">
                    <p class="text-xs sm:text-sm font-medium text-red-700">04:00 PM</p>
                    <p class="text-xs text-red-600 mt-0.5 sm:mt-1">Booked</p>
                </div>
                <div class="p-2 sm:p-3 border-2 border-green-300 bg-green-50 rounded-lg text-center cursor-pointer hover:bg-green-100">
                    <p class="text-xs sm:text-sm font-medium text-green-700">05:00 PM</p>
                    <p class="text-xs text-green-600 mt-0.5 sm:mt-1">Available</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="mt-4 sm:mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 mb-3">Legend</h4>
    <div class="flex flex-wrap gap-3 sm:gap-4">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-100 border-2 border-green-300 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Available</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-100 border-2 border-red-300 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Booked</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-100 border-2 border-blue-300 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Completed</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-gray-100 border-2 border-gray-300 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Break/Unavailable</span>
        </div>
    </div>
</div>
@endsection
