@extends('layouts.doctor')

@section('title', 'My Appointments')

@section('page-title', 'My Appointments')

@section('content')
    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date"
                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select
                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    <option>All Status</option>
                    <option>Scheduled</option>
                    <option>Completed</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Patient</label>
                <input type="text" placeholder="Search by name..."
                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
            </div>
            <div class="flex items-end">
                <button
                    class="w-full px-4 sm:px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm sm:text-base">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 sm:p-6 border-b">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Today's Appointments - November 16, 2025</h3>
        </div>

        <!-- Appointment Cards -->
        <div class="divide-y">
            <!-- Appointment 1 -->
            <div class="p-4 sm:p-6 hover:bg-gray-50">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 flex-1">
                        <div
                            class="w-16 h-16 bg-sky-100 rounded-lg flex flex-col items-center justify-center shrink-0">
                            <span class="text-xs text-sky-600 font-medium">09:00</span>
                            <span class="text-xs text-gray-500">AM</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                <img src="https://ui-avatars.com/api/?name=John+Smith&background=10b981&color=fff"
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full" alt="Patient">
                                <div>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-800">John Smith</h4>
                                    <p class="text-xs sm:text-sm text-gray-500">ID: PT-2025-001 • Age: 45 • Male</p>
                                </div>
                            </div>
                            <div class="mt-3 space-y-2">
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Reason:</span> Chest
                                    pain and irregular heartbeat</p>
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Phone:</span> +1 (555)
                                    123-4567</p>
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Allergies:</span>
                                    Penicillin</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-start lg:items-end gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs sm:text-sm font-medium rounded-full">
                            Scheduled
                        </span>
                        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto mt-2">
                            <a href="{{ route('doctor.appointment-details', ['id' => 1]) }}"
                                class="px-3 sm:px-4 py-2 bg-sky-600 text-white text-xs sm:text-sm rounded-lg hover:bg-sky-700 text-center">
                                View Details
                            </a>
                            <button
                                class="px-3 sm:px-4 py-2 bg-green-600 text-white text-xs sm:text-sm rounded-lg hover:bg-green-700">
                                Mark Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment 2 -->
            <div class="p-4 sm:p-6 hover:bg-gray-50">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 flex-1">
                        <div
                            class="w-16 h-16 bg-sky-100 rounded-lg flex flex-col items-center justify-center shrink-0">
                            <span class="text-xs text-sky-600 font-medium">10:30</span>
                            <span class="text-xs text-gray-500">AM</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                <img src="https://ui-avatars.com/api/?name=Emily+Davis&background=f59e0b&color=fff"
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full" alt="Patient">
                                <div>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-800">Emily Davis</h4>
                                    <p class="text-xs sm:text-sm text-gray-500">ID: PT-2025-002 • Age: 52 • Female</p>
                                </div>
                            </div>
                            <div class="mt-3 space-y-2">
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Reason:</span>
                                    Follow-up consultation for hypertension</p>
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Phone:</span> +1 (555)
                                    234-5678</p>
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Allergies:</span> None
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-start lg:items-end gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs sm:text-sm font-medium rounded-full">
                            Scheduled
                        </span>
                        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto mt-2">
                            <a href="{{ route('doctor.appointment-details', ['id' => 1]) }}"
                                class="px-3 sm:px-4 py-2 bg-sky-600 text-white text-xs sm:text-sm rounded-lg hover:bg-sky-700 text-center">
                                View Details
                            </a>
                            <button
                                class="px-3 sm:px-4 py-2 bg-green-600 text-white text-xs sm:text-sm rounded-lg hover:bg-green-700">
                                Mark Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment 3 -->
            <div class="p-4 sm:p-6 hover:bg-gray-50">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 flex-1">
                        <div
                            class="w-16 h-16 bg-sky-100 rounded-lg flex flex-col items-center justify-center shrink-0">
                            <span class="text-xs text-sky-600 font-medium">02:00</span>
                            <span class="text-xs text-gray-500">PM</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                <img src="https://ui-avatars.com/api/?name=Robert+Wilson&background=8b5cf6&color=fff"
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full" alt="Patient">
                                <div>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-800">Robert Wilson</h4>
                                    <p class="text-xs sm:text-sm text-gray-500">ID: PT-2025-003 • Age: 38 • Male</p>
                                </div>
                            </div>
                            <div class="mt-3 space-y-2">
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Reason:</span> Annual
                                    cardiac checkup</p>
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Phone:</span> +1 (555)
                                    345-6789</p>
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Allergies:</span>
                                    Aspirin</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-start lg:items-end gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs sm:text-sm font-medium rounded-full">
                            Scheduled
                        </span>
                        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto mt-2">
                            <a href="{{ route('doctor.appointment-details', ['id' => 1]) }}"
                                class="px-3 sm:px-4 py-2 bg-sky-600 text-white text-xs sm:text-sm rounded-lg hover:bg-sky-700 text-center">
                                View Details
                            </a>
                            <button
                                class="px-3 sm:px-4 py-2 bg-green-600 text-white text-xs sm:text-sm rounded-lg hover:bg-green-700">
                                Mark Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment 4 - Completed -->
            <div class="p-4 sm:p-6 hover:bg-gray-50 opacity-60">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 flex-1">
                        <div
                            class="w-16 h-16 bg-gray-100 rounded-lg flex flex-col items-center justify-center shrink-0">
                            <span class="text-xs text-gray-600 font-medium">03:30</span>
                            <span class="text-xs text-gray-500">PM</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                <img src="https://ui-avatars.com/api/?name=Lisa+Anderson&background=ec4899&color=fff"
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full" alt="Patient">
                                <div>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-800">Lisa Anderson</h4>
                                    <p class="text-xs sm:text-sm text-gray-500">ID: PT-2025-004 • Age: 29 • Female</p>
                                </div>
                            </div>
                            <div class="mt-3 space-y-2">
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Reason:</span> Heart
                                    palpitations during exercise</p>
                                <p class="text-xs sm:text-sm text-gray-700"><span class="font-medium">Phone:</span> +1
                                    (555) 456-7890</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-start lg:items-end gap-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs sm:text-sm font-medium rounded-full">
                            Completed
                        </span>
                        <div class="flex gap-2 w-full lg:w-auto mt-2">
                            <a href="{{ route('doctor.appointment-details', ['id' => 1]) }}"
                                class="px-3 sm:px-4 py-2 bg-gray-600 text-white text-xs sm:text-sm rounded-lg hover:bg-gray-700 text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs sm:text-sm text-gray-600">Showing 1 to 4 of 12 appointments</p>
        <div class="flex flex-wrap gap-2 justify-center">
            <button
                class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-xs sm:text-sm">Previous</button>
            <button class="px-3 sm:px-4 py-2 bg-sky-600 text-white rounded-lg text-xs sm:text-sm">1</button>
            <button
                class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-xs sm:text-sm">2</button>
            <button
                class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-xs sm:text-sm">3</button>
            <button
                class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-xs sm:text-sm">Next</button>
        </div>
    </div>
@endsection
