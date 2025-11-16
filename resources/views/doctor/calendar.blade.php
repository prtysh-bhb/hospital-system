@extends('layouts.doctor')

@section('title', 'My Schedule')

@section('page-title', 'My Schedule & Availability')

@section('header-actions')
<button class="px-3 sm:px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm sm:text-base">
    + Set Availability
</button>
@endsection

@section('content')
<!-- Calendar Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
    <div class="p-4 sm:p-6 border-b flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3 sm:gap-4">
            <button class="px-2 sm:px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800">November 2025</h3>
            <button class="px-2 sm:px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <button class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-sky-600 text-white rounded-lg text-xs sm:text-sm">Month</button>
            <button class="flex-1 sm:flex-none px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:bg-gray-50">Week</button>
            <button class="flex-1 sm:flex-none px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:bg-gray-50">Day</button>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="p-3 sm:p-6">
        <!-- Days Header -->
        <div class="grid grid-cols-7 gap-2 sm:gap-4 mb-3 sm:mb-4">
            <div class="text-center text-xs sm:text-sm font-semibold text-gray-600">Sun</div>
            <div class="text-center text-xs sm:text-sm font-semibold text-gray-600">Mon</div>
            <div class="text-center text-xs sm:text-sm font-semibold text-gray-600">Tue</div>
            <div class="text-center text-xs sm:text-sm font-semibold text-gray-600">Wed</div>
            <div class="text-center text-xs sm:text-sm font-semibold text-gray-600">Thu</div>
            <div class="text-center text-xs sm:text-sm font-semibold text-gray-600">Fri</div>
            <div class="text-center text-xs sm:text-sm font-semibold text-gray-600">Sat</div>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-1 sm:gap-2 md:gap-4">
            <!-- Previous month days -->
            <div class="aspect-square border border-gray-200 rounded-lg p-1 sm:p-2 bg-gray-50">
                <div class="text-xs sm:text-sm text-gray-400">27</div>
            </div>
            <div class="aspect-square border border-gray-200 rounded-lg p-1 sm:p-2 bg-gray-50">
                <div class="text-xs sm:text-sm text-gray-400">28</div>
            </div>
            <div class="aspect-square border border-gray-200 rounded-lg p-1 sm:p-2 bg-gray-50">
                <div class="text-xs sm:text-sm text-gray-400">29</div>
            </div>
            <div class="aspect-square border border-gray-200 rounded-lg p-1 sm:p-2 bg-gray-50">
                <div class="text-xs sm:text-sm text-gray-400">30</div>
            </div>
            <div class="aspect-square border border-gray-200 rounded-lg p-1 sm:p-2 bg-gray-50">
                <div class="text-xs sm:text-sm text-gray-400">31</div>
            </div>

            <!-- November days -->
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">1</div>
                <div class="mt-1 space-y-0.5 sm:space-y-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">9 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">2 PM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">2</div>
            </div>

            <!-- Week 2 -->
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 bg-red-50">
                <div class="text-xs sm:text-sm font-medium text-gray-800">3</div>
                <div class="mt-1 hidden sm:block">
                    <div class="text-xs bg-red-100 text-red-700 px-1 py-0.5 rounded">Unavailable</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">4</div>
                <div class="mt-1 space-y-0.5 sm:space-y-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">9 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">11 AM</div>
                    <div class="text-xs bg-blue-100 text-blue-700 px-1 py-0.5 rounded">3 PM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">5</div>
                <div class="mt-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">10 AM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">6</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">7</div>
                <div class="mt-1 space-y-0.5 sm:space-y-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">9 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">1 PM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">8</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">9</div>
            </div>

            <!-- Week 3 -->
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 bg-red-50">
                <div class="text-xs sm:text-sm font-medium text-gray-800">10</div>
                <div class="mt-1 hidden sm:block">
                    <div class="text-xs bg-red-100 text-red-700 px-1 py-0.5 rounded">Leave</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">11</div>
                <div class="mt-1 space-y-0.5 sm:space-y-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">9 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">11 AM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">12</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">13</div>
                <div class="mt-1 hidden sm:block">
                    <div class="text-xs bg-blue-100 text-blue-700 px-1 py-0.5 rounded">2 PM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">14</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">15</div>
                <div class="mt-1 space-y-0.5 sm:space-y-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">10 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">3 PM</div>
                </div>
            </div>
            <div class="aspect-square border-2 border-sky-600 bg-sky-50 rounded-lg p-1 sm:p-2">
                <div class="text-xs sm:text-sm font-bold text-sky-700">16</div>
                <div class="mt-1 space-y-0.5 sm:space-y-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">9 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">10:30 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">2 PM</div>
                </div>
            </div>

            <!-- Week 4 -->
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">17</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">18</div>
                <div class="mt-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">11 AM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">19</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">20</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">21</div>
                <div class="mt-1 space-y-0.5 sm:space-y-1 hidden sm:block">
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">9 AM</div>
                    <div class="text-xs bg-green-100 text-green-700 px-1 py-0.5 rounded">2 PM</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">22</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">23</div>
            </div>

            <!-- Week 5 -->
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">24</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">25</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">26</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 bg-red-50">
                <div class="text-xs sm:text-sm font-medium text-gray-800">27</div>
                <div class="mt-1 hidden sm:block">
                    <div class="text-xs bg-red-100 text-red-700 px-1 py-0.5 rounded">Holiday</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 bg-red-50">
                <div class="text-xs sm:text-sm font-medium text-gray-800">28</div>
                <div class="mt-1 hidden sm:block">
                    <div class="text-xs bg-red-100 text-red-700 px-1 py-0.5 rounded">Holiday</div>
                </div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">29</div>
            </div>
            <div class="aspect-square border border-gray-300 rounded-lg p-1 sm:p-2 hover:border-sky-500 cursor-pointer">
                <div class="text-xs sm:text-sm font-medium text-gray-800">30</div>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Availability Settings -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-4 sm:p-6 border-b">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Weekly Availability Schedule</h3>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Set your regular working hours for each day</p>
    </div>
    <div class="p-4 sm:p-6">
        <div class="space-y-3 sm:space-y-4">
            <!-- Monday -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <input type="checkbox" checked class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded">
                        <span class="font-medium text-gray-800 text-sm sm:text-base sm:w-24">Monday</span>
                    </div>
                    <div class="flex items-center gap-2 flex-1">
                        <input type="time" value="09:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                        <span class="text-gray-500 text-xs sm:text-sm">to</span>
                        <input type="time" value="17:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                    </div>
                </div>
                <button class="px-3 sm:px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg text-sm sm:text-base">Edit</button>
            </div>

            <!-- Tuesday -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <input type="checkbox" checked class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded">
                        <span class="font-medium text-gray-800 text-sm sm:text-base sm:w-24">Tuesday</span>
                    </div>
                    <div class="flex items-center gap-2 flex-1">
                        <input type="time" value="09:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                        <span class="text-gray-500 text-xs sm:text-sm">to</span>
                        <input type="time" value="17:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                    </div>
                </div>
                <button class="px-3 sm:px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg text-sm sm:text-base">Edit</button>
            </div>

            <!-- Wednesday -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <input type="checkbox" checked class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded">
                        <span class="font-medium text-gray-800 text-sm sm:text-base sm:w-24">Wednesday</span>
                    </div>
                    <div class="flex items-center gap-2 flex-1">
                        <input type="time" value="09:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                        <span class="text-gray-500 text-xs sm:text-sm">to</span>
                        <input type="time" value="17:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                    </div>
                </div>
                <button class="px-3 sm:px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg text-sm sm:text-base">Edit</button>
            </div>

            <!-- Thursday -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <input type="checkbox" checked class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded">
                        <span class="font-medium text-gray-800 text-sm sm:text-base sm:w-24">Thursday</span>
                    </div>
                    <div class="flex items-center gap-2 flex-1">
                        <input type="time" value="09:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                        <span class="text-gray-500 text-xs sm:text-sm">to</span>
                        <input type="time" value="17:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                    </div>
                </div>
                <button class="px-3 sm:px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg text-sm sm:text-base">Edit</button>
            </div>

            <!-- Friday -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <input type="checkbox" checked class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded">
                        <span class="font-medium text-gray-800 text-sm sm:text-base sm:w-24">Friday</span>
                    </div>
                    <div class="flex items-center gap-2 flex-1">
                        <input type="time" value="09:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                        <span class="text-gray-500 text-xs sm:text-sm">to</span>
                        <input type="time" value="13:00" class="px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none">
                    </div>
                </div>
                <button class="px-3 sm:px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg text-sm sm:text-base">Edit</button>
            </div>

            <!-- Saturday -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <input type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded">
                        <span class="font-medium text-gray-500 text-sm sm:text-base sm:w-24">Saturday</span>
                    </div>
                    <span class="text-gray-400 text-sm sm:text-base">Unavailable</span>
                </div>
                <button class="px-3 sm:px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm sm:text-base">Enable</button>
            </div>

            <!-- Sunday -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <input type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded">
                        <span class="font-medium text-gray-500 text-sm sm:text-base sm:w-24">Sunday</span>
                    </div>
                    <span class="text-gray-400 text-sm sm:text-base">Unavailable</span>
                </div>
                <button class="px-3 sm:px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm sm:text-base">Enable</button>
            </div>
        </div>

        <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-end gap-3">
            <button class="px-4 sm:px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm sm:text-base">
                Cancel
            </button>
            <button class="px-4 sm:px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm sm:text-base">
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="mt-4 sm:mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <h4 class="text-xs sm:text-sm font-semibold text-gray-800 mb-3">Legend</h4>
    <div class="flex flex-wrap gap-3 sm:gap-4">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-100 border border-green-200 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Scheduled Appointment</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-100 border border-blue-200 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Completed</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-100 border border-red-200 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Unavailable/Leave</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-sky-100 border-2 border-sky-600 rounded"></div>
            <span class="text-xs sm:text-sm text-gray-600">Today</span>
        </div>
    </div>
</div>
@endsection
