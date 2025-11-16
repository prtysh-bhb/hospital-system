@extends('layouts.frontdesk')

@section('title', 'Appointment History')

@section('page-title', 'Appointment History')

@section('header-actions')
<button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
    Export Report
</button>
@endsection

@section('content')
<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
            <input type="date" value="2025-10-01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
            <input type="date" value="2025-11-16" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <option>All Status</option>
                <option>Completed</option>
                <option>Cancelled</option>
                <option>No-Show</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" placeholder="Patient or Doctor name..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
        </div>
        <div class="flex items-end">
            <button class="w-full px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                Search
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Appointments</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">842</p>
            </div>
            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Completed</p>
                <p class="text-3xl font-bold text-green-600 mt-2">712</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-3">84.6% success rate</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Cancelled</p>
                <p class="text-3xl font-bold text-red-600 mt-2">98</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-3">11.6% cancellation</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">No-Show</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">32</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-3">3.8% no-show rate</p>
    </div>
</div>

<!-- Appointments History Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Past Appointments</h3>
        <p class="text-sm text-gray-500 mt-1">Showing appointments from Oct 1, 2025 to Nov 16, 2025</p>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Appointment ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date & Time
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Patient
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Doctor
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fee
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Row 1 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">APT-2025-842</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 15, 2025</p>
                        <p class="text-sm text-gray-500">03:00 PM</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=Lisa+Anderson&background=ec4899&color=fff"
                                 class="w-8 h-8 rounded-full" alt="Patient">
                            <p class="text-sm font-medium text-gray-900">Lisa Anderson</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Dr. Sarah Johnson</p>
                        <p class="text-sm text-gray-500">Cardiologist</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                            Completed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">$150.00</p>
                        <p class="text-xs text-green-600">Paid</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-sky-600 hover:text-sky-800">View</button>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">APT-2025-841</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 15, 2025</p>
                        <p class="text-sm text-gray-500">11:00 AM</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=Robert+Wilson&background=8b5cf6&color=fff"
                                 class="w-8 h-8 rounded-full" alt="Patient">
                            <p class="text-sm font-medium text-gray-900">Robert Wilson</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Dr. Michael Smith</p>
                        <p class="text-sm text-gray-500">Pediatrician</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">
                            Cancelled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">$120.00</p>
                        <p class="text-xs text-gray-600">Refunded</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-sky-600 hover:text-sky-800">View</button>
                    </td>
                </tr>

                <!-- Row 3 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">APT-2025-840</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 14, 2025</p>
                        <p class="text-sm text-gray-500">02:30 PM</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=Emily+Davis&background=f59e0b&color=fff"
                                 class="w-8 h-8 rounded-full" alt="Patient">
                            <p class="text-sm font-medium text-gray-900">Emily Davis</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Dr. Sarah Johnson</p>
                        <p class="text-sm text-gray-500">Cardiologist</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                            Completed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">$150.00</p>
                        <p class="text-xs text-green-600">Paid</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-sky-600 hover:text-sky-800">View</button>
                    </td>
                </tr>

                <!-- Row 4 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">APT-2025-839</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 14, 2025</p>
                        <p class="text-sm text-gray-500">10:00 AM</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=John+Smith&background=10b981&color=fff"
                                 class="w-8 h-8 rounded-full" alt="Patient">
                            <p class="text-sm font-medium text-gray-900">John Smith</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Dr. David Brown</p>
                        <p class="text-sm text-gray-500">Dermatologist</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-medium rounded-full">
                            No-Show
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">$100.00</p>
                        <p class="text-xs text-red-600">Unpaid</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-sky-600 hover:text-sky-800">View</button>
                    </td>
                </tr>

                <!-- Row 5 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">APT-2025-838</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 13, 2025</p>
                        <p class="text-sm text-gray-500">04:00 PM</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=Sarah+Martinez&background=ef4444&color=fff"
                                 class="w-8 h-8 rounded-full" alt="Patient">
                            <p class="text-sm font-medium text-gray-900">Sarah Martinez</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Dr. Jennifer Davis</p>
                        <p class="text-sm text-gray-500">Neurologist</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                            Completed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">$180.00</p>
                        <p class="text-xs text-green-600">Paid</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-sky-600 hover:text-sky-800">View</button>
                    </td>
                </tr>

                <!-- Row 6 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">APT-2025-837</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 12, 2025</p>
                        <p class="text-sm text-gray-500">09:30 AM</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=Michael+Johnson&background=0ea5e9&color=fff"
                                 class="w-8 h-8 rounded-full" alt="Patient">
                            <p class="text-sm font-medium text-gray-900">Michael Johnson</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Dr. Emily Williams</p>
                        <p class="text-sm text-gray-500">Orthopedic</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                            Completed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">$200.00</p>
                        <p class="text-xs text-green-600">Paid</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-sky-600 hover:text-sky-800">View</button>
                    </td>
                </tr>

                <!-- Row 7 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">APT-2025-836</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 11, 2025</p>
                        <p class="text-sm text-gray-500">01:00 PM</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=David+Lee&background=14b8a6&color=fff"
                                 class="w-8 h-8 rounded-full" alt="Patient">
                            <p class="text-sm font-medium text-gray-900">David Lee</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Dr. Michael Smith</p>
                        <p class="text-sm text-gray-500">Pediatrician</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                            Completed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">$120.00</p>
                        <p class="text-xs text-green-600">Paid</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-sky-600 hover:text-sky-800">View</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t flex items-center justify-between">
        <p class="text-sm text-gray-600">Showing 1 to 7 of 842 appointments</p>
        <div class="flex space-x-2">
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Previous</button>
            <button class="px-4 py-2 bg-sky-600 text-white rounded-lg">1</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">2</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">3</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">...</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">121</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Next</button>
        </div>
    </div>
</div>
@endsection
