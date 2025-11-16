@extends('layouts.frontdesk')

@section('title', 'Patients')

@section('page-title', 'Patients')

@section('content')
<!-- Search & Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Patient</label>
            <input type="text" placeholder="Search by name, email, phone, or ID..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <option>All Genders</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="w-full px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                Search
            </button>
        </div>
    </div>
</div>

<!-- Patients Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">All Patients</h3>
            <p class="text-sm text-gray-500 mt-1">View-only access to patient records</p>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold text-gray-800">1,234</p>
            <p class="text-sm text-gray-500">Total Patients</p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Patient ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Patient Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Age/Gender
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Last Visit
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
                        <p class="text-sm font-medium text-gray-900">PT-2024-001</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=John+Smith&background=10b981&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Patient">
                            <div>
                                <p class="text-sm font-medium text-gray-900">John Smith</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">45 • Male</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">john.smith@email.com</p>
                        <p class="text-sm text-gray-500">+1 (555) 123-4567</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 16, 2025</p>
                        <p class="text-sm text-gray-500">Dr. Sarah Johnson</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                            View Details
                        </button>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">PT-2024-002</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=Emily+Davis&background=f59e0b&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Patient">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Emily Davis</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">52 • Female</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">emily.davis@email.com</p>
                        <p class="text-sm text-gray-500">+1 (555) 234-5678</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 14, 2025</p>
                        <p class="text-sm text-gray-500">Dr. Sarah Johnson</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                            View Details
                        </button>
                    </td>
                </tr>

                <!-- Row 3 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">PT-2024-003</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=Robert+Wilson&background=8b5cf6&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Patient">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Robert Wilson</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">38 • Male</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">robert.wilson@email.com</p>
                        <p class="text-sm text-gray-500">+1 (555) 345-6789</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 10, 2025</p>
                        <p class="text-sm text-gray-500">Dr. Michael Smith</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                            View Details
                        </button>
                    </td>
                </tr>

                <!-- Row 4 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">PT-2024-004</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=Lisa+Anderson&background=ec4899&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Patient">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Lisa Anderson</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">29 • Female</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">lisa.anderson@email.com</p>
                        <p class="text-sm text-gray-500">+1 (555) 456-7890</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 8, 2025</p>
                        <p class="text-sm text-gray-500">Dr. Emily Williams</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                            View Details
                        </button>
                    </td>
                </tr>

                <!-- Row 5 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">PT-2024-005</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=Michael+Johnson&background=0ea5e9&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Patient">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Michael Johnson</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">61 • Male</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">michael.j@email.com</p>
                        <p class="text-sm text-gray-500">+1 (555) 567-8901</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 5, 2025</p>
                        <p class="text-sm text-gray-500">Dr. David Brown</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                            View Details
                        </button>
                    </td>
                </tr>

                <!-- Row 6 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">PT-2024-006</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=Sarah+Martinez&background=ef4444&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Patient">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Sarah Martinez</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">34 • Female</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">sarah.m@email.com</p>
                        <p class="text-sm text-gray-500">+1 (555) 678-9012</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Nov 3, 2025</p>
                        <p class="text-sm text-gray-500">Dr. Jennifer Davis</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                            View Details
                        </button>
                    </td>
                </tr>

                <!-- Row 7 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-gray-900">PT-2024-007</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=David+Lee&background=14b8a6&color=fff"
                                 class="w-10 h-10 rounded-full" alt="Patient">
                            <div>
                                <p class="text-sm font-medium text-gray-900">David Lee</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">27 • Male</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">david.lee@email.com</p>
                        <p class="text-sm text-gray-500">+1 (555) 789-0123</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-900">Oct 28, 2025</p>
                        <p class="text-sm text-gray-500">Dr. Michael Smith</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                            View Details
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t flex items-center justify-between">
        <p class="text-sm text-gray-600">Showing 1 to 7 of 1,234 patients</p>
        <div class="flex space-x-2">
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Previous</button>
            <button class="px-4 py-2 bg-sky-600 text-white rounded-lg">1</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">2</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">3</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">...</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">176</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Next</button>
        </div>
    </div>
</div>

<!-- Note -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex">
        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <p class="text-sm font-medium text-blue-800">Read-Only Access</p>
            <p class="text-sm text-blue-700 mt-1">
                You have view-only access to patient records. To modify patient information or access medical records, please contact an administrator.
            </p>
        </div>
    </div>
</div>
@endsection
