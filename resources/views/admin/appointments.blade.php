@extends('layouts.admin')

@section('title', 'Appointments Management')
@section('page-title', 'Appointments Management')

@section('header-actions')
    <a href="{{ route('admin.add-appointment') }}"
        class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">+
        Add Appointment</a>
@endsection

@section('content')
    <!-- Filters -->
    <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" id="filterSearch" placeholder="Patient name, ID..."
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Doctor</label>
                <select id="filterDoctor"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option value="">All Doctors</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="filterDate"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="filterStatus"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="checked_in">Checked In</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="no_show">No Show</option>
                </select>
            </div>
            <div class="flex items-end">
                <button id="applyFiltersBtn"
                    class="w-full px-4 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">Apply
                    Filters</button>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Appointment ID</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Patient</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                            Doctor</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Date & Time</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                            Type</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsTableBody" class="divide-y divide-gray-200">
                    <!-- Dynamic Rows will load here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">
            <div id="paginationInfo" class="text-xs sm:text-sm text-gray-600"></div>
            <div id="paginationContainer" class="flex flex-wrap gap-2 justify-center"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                loadAppointments(1); // load default page
            });

            // Apply Filters Button Click
            $('#applyFiltersBtn').on('click', function() {
                loadAppointments(1); // reload first page with filters
            });

            function getFilters() {
                return {
                    search: $('#filterSearch').val(),
                    doctor_id: $('#filterDoctor').val(), // must match backend
                    date: $('#filterDate').val(),
                    status: $('#filterStatus').val() // backend already expects 'status'
                };
            }

            function loadAppointments(page = 1) {
                let filters = getFilters();

                let query = `?page=${page}`;
                if (filters.search) query += `&search=${filters.search}`;
                if (filters.doctor_id) query += `&doctor_id=${filters.doctor_id}`; // fix here
                if (filters.date) query += `&date=${filters.date}`;
                if (filters.status) query += `&status=${filters.status}`;

                fetch("{{ route('admin.appointments.list') }}" + query)
                    .then(response => response.json())
                    .then(res => {
                        let data = res.data;
                        let tbody = document.getElementById("appointmentsTableBody");
                        tbody.innerHTML = "";

                        if (data.data.length === 0) {
                            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">No Appointments Found</td>
                </tr>`;
                            document.querySelector("#paginationContainer").innerHTML = '';
                            document.querySelector("#paginationInfo").innerHTML = '';
                            return;
                        }

                        data.data.forEach(app => {
                            let row = `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><span class="text-sm font-medium text-sky-600">#APT${app.appointment_number}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 font-semibold">
                                ${app.patient.last_name.substring(0,2).toUpperCase()}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-800">${app.patient.first_name} ${app.patient.last_name}</p>
                                <p class="text-xs text-gray-500">${app.patient.phone}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <p class="text-sm font-medium text-gray-800">${app.doctor.first_name} ${app.doctor.last_name}</p>
                        <p class="text-xs text-gray-500">
                            ${app.doctor.doctor_profile && app.doctor.doctor_profile.specialty 
                                ? app.doctor.doctor_profile.specialty.name 
                                : 'N/A'}
                        </p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-sm text-gray-800">${app.formatted_date} , ${app.formatted_time}</p>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="px-3 py-1 text-xs font-medium ${getTypeColor(app.appointment_type)} rounded-full">
                            ${formatLabel(app.appointment_type)}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 text-xs font-medium ${getStatusColor(app.status)} rounded-full">
                            ${formatLabel(app.status)}
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-3 sm:py-4">
                        <div class="flex space-x-1 sm:space-x-2">
                            <button class="text-sky-600 hover:text-sky-800">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button class="text-amber-600 hover:text-amber-800">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>`;
                            tbody.innerHTML += row;
                        });

                        updatePagination(data);
                    });
            }


            // Helper Functions
            function formatLabel(text) {
                if (!text) return "";
                return text.replace(/_/g, " ").toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
            }

            function getStatusColor(status) {
                status = status.toLowerCase();
                switch (status) {
                    case 'confirmed':
                        return 'bg-green-100 text-green-700';
                    case 'pending':
                        return 'bg-yellow-100 text-yellow-700';
                    case 'checked_in':
                        return 'bg-indigo-100 text-indigo-700';
                    case 'in_progress':
                        return 'bg-blue-100 text-blue-700';
                    case 'completed':
                        return 'bg-emerald-100 text-emerald-700';
                    case 'cancelled':
                        return 'bg-red-100 text-red-700';
                    case 'no_show':
                        return 'bg-gray-200 text-gray-700';
                    default:
                        return 'bg-gray-100 text-gray-700';
                }
            }

            function getTypeColor(type) {
                type = type.toLowerCase();
                switch (type) {
                    case 'consultation':
                        return 'bg-blue-100 text-blue-700';
                    case 'follow_up':
                        return 'bg-purple-100 text-purple-700';
                    case 'emergency':
                        return 'bg-red-100 text-red-700';
                    case 'check_up':
                        return 'bg-blue-100 text-blue-700';
                    default:
                        return 'bg-gray-100 text-gray-700';
                }
            }

            function updatePagination(data) {
                let paginationHTML = "";

                paginationHTML += `
                <button onclick="loadAppointments(${data.prev_page_url ? data.current_page - 1 : data.current_page})"
                    class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm ${data.prev_page_url ? 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50' : 'text-gray-400 bg-gray-100 cursor-not-allowed'} rounded-lg">
                    Previous
                </button>
            `;

                for (let i = 1; i <= data.last_page; i++) {
                    paginationHTML += `
                    <button onclick="loadAppointments(${i})"
                        class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm ${i === data.current_page ? 'text-white bg-sky-600' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'} rounded-lg">
                        ${i}
                    </button>`;
                }

                paginationHTML += `
                <button onclick="loadAppointments(${data.next_page_url ? data.current_page + 1 : data.current_page})"
                    class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm ${data.next_page_url ? 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50' : 'text-gray-400 bg-gray-100 cursor-not-allowed'} rounded-lg">
                    Next
                </button>
            `;

                document.querySelector("#paginationContainer").innerHTML = paginationHTML;
                document.querySelector("#paginationInfo").innerHTML =
                    `Showing <span class="font-medium">${data.from}</span> to <span class="font-medium">${data.to}</span> of <span class="font-medium">${data.total}</span> results`;
            }
        </script>
    @endpush
@endsection
