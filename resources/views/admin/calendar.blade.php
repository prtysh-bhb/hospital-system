@extends('layouts.admin')

@section('title', 'Calendar')

@section('page-title', 'Appointments Calendar')

@section('header-actions')
<select class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
    <option>All Doctors</option>
    <option>Dr. Sharma</option>
    <option>Dr. Mehta</option>
    <option>Dr. Singh</option>
</select>
<a href="{{ route('admin.add-appointment') }}" class="px-6 py-2 text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">+ New Appointment</a>
@endsection

@section('content')
<!-- Calendar Controls -->
<div class="bg-white p-3 sm:p-4 rounded-lg sm:rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">
        <div class="flex items-center space-x-2 sm:space-x-4">
            <button class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">December 2025</h3>
            <button class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        <div class="flex gap-2">
            <button class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-white bg-sky-600 rounded-lg">Month</button>
            <button class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Week</button>
            <button class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Day</button>
        </div>
    </div>
</div>

<!-- Month Calendar View -->
<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
    <!-- Calendar Header -->
    <div class="grid grid-cols-7 border-b border-gray-200">
        <div class="p-2 sm:p-4 text-center">
            <p class="text-xs font-semibold text-gray-600 uppercase">Sun</p>
        </div>
        <div class="p-4 text-center">
            <p class="text-xs font-semibold text-gray-600 uppercase">Mon</p>
        </div>
        <div class="p-4 text-center">
            <p class="text-xs font-semibold text-gray-600 uppercase">Tue</p>
        </div>
        <div class="p-4 text-center">
            <p class="text-xs font-semibold text-gray-600 uppercase">Wed</p>
        </div>
        <div class="p-4 text-center">
            <p class="text-xs font-semibold text-gray-600 uppercase">Thu</p>
        </div>
        <div class="p-4 text-center">
            <p class="text-xs font-semibold text-gray-600 uppercase">Fri</p>
        </div>
        <div class="p-4 text-center">
            <p class="text-xs font-semibold text-gray-600 uppercase">Sat</p>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7">
        <!-- Week 1 -->
        <div class="p-4 border-r border-b border-gray-100 h-32 bg-gray-50">
            <p class="text-sm text-gray-400">26</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32 bg-gray-50">
            <p class="text-sm text-gray-400">27</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32 bg-gray-50">
            <p class="text-sm text-gray-400">28</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32 bg-gray-50">
            <p class="text-sm text-gray-400">29</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32 bg-gray-50">
            <p class="text-sm text-gray-400">30</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">1</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-sky-100 text-sky-700 rounded truncate">10:00 Dr. Sharma</div>
                <div class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded truncate">2:00 Dr. Mehta</div>
            </div>
        </div>
        <div class="p-4 border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">2</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded truncate">9:00 Dr. Singh</div>
            </div>
        </div>

        <!-- Week 2 -->
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">3</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">4</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-sky-100 text-sky-700 rounded truncate">11:00 Dr. Sharma</div>
                <div class="text-xs px-2 py-1 bg-amber-100 text-amber-700 rounded truncate">3:00 Dr. Gupta</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">5</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded truncate">10:30 Dr. Mehta</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">6</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-sky-100 text-sky-700 rounded truncate">9:30 Dr. Sharma</div>
                <div class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded truncate">1:00 Dr. Singh</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">7</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">8</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded truncate">2:00 Dr. Mehta</div>
            </div>
        </div>
        <div class="p-4 border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">9</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded truncate">10:00 Dr. Singh</div>
            </div>
        </div>

        <!-- Week 3 -->
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">10</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">11</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-sky-100 text-sky-700 rounded truncate">10:00 Dr. Sharma</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">12</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded truncate">11:00 Dr. Mehta</div>
                <div class="text-xs px-2 py-1 bg-amber-100 text-amber-700 rounded truncate">4:00 Dr. Gupta</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">13</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">14</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded truncate">9:00 Dr. Singh</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32 bg-sky-50">
            <p class="text-sm font-semibold text-sky-700 mb-2">15 • Today</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-sky-200 text-sky-800 rounded truncate font-medium">10:30 Dr. Sharma</div>
                <div class="text-xs px-2 py-1 bg-purple-200 text-purple-800 rounded truncate font-medium">11:00 Dr. Mehta</div>
                <div class="text-xs text-sky-700 font-medium">+5 more</div>
            </div>
        </div>
        <div class="p-4 border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">16</p>
        </div>

        <!-- Week 4 -->
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">17</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">18</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-sky-100 text-sky-700 rounded truncate">2:00 Dr. Sharma</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">19</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded truncate">10:00 Dr. Mehta</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">20</p>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">21</p>
            <div class="space-y-1">
                <div class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded truncate">11:30 Dr. Singh</div>
            </div>
        </div>
        <div class="p-4 border-r border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">22</p>
        </div>
        <div class="p-4 border-b border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">23</p>
        </div>

        <!-- Week 5 -->
        <div class="p-4 border-r border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">24</p>
        </div>
        <div class="p-4 border-r border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">25</p>
        </div>
        <div class="p-4 border-r border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">26</p>
        </div>
        <div class="p-4 border-r border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">27</p>
        </div>
        <div class="p-4 border-r border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">28</p>
        </div>
        <div class="p-4 border-r border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">29</p>
        </div>
        <div class="p-4 border-gray-100 h-32">
            <p class="text-sm font-semibold text-gray-800 mb-2">30</p>
        </div>
    </div>
</div>
@endsection
