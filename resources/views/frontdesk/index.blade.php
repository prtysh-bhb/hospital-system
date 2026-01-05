@extends('layouts.frontdesk')

@section('title', 'Appointments')
@section('page-title', 'All Appointments')

@section('header-actions')
    <a href="{{ route('frontdesk.add-appointment') }}"
        class="px-4 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium"
        {{ request()->routeIs('frontdesk.appointments*') ? 'text-white bg-sky-600' : 'text-gray-700 hover:bg-gray-100' }}>+
        Add Appointment</a>
@endsection

@section('content')
    <!-- Filters -->
    <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" id="filterSearch" placeholder="Patient name"
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
                    @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $st)
                        <option value="{{ $st }}" @selected(request('status') == $st)>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Type</label>
                <select id="filterType"
                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option value="">All Type</option>
                    @foreach (['consultation', 'follow_up', 'emergency', 'check_up'] as $tp)
                        <option value="{{ $tp }}" @selected(request('type') == $tp)>
                            {{ ucfirst(str_replace('_', ' ', $tp)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        {{-- Header --}}
        {{-- <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">All Appointments</h2>
                <p class="text-sm text-gray-500">Manage appointment status</p>
            </div>

            <a href="{{ route('frontdesk.add-appointment') }}"
                class="px-4 py-2 bg-sky-600 text-white text-sm rounded-lg hover:bg-sky-700">
                + Add Appointment
            </a>
        </div> --}}

        <!-- Appointments Table -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100" id="appointmentTable">
            <div class="overflow-x-auto"></div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Patient</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Doctor</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                            Date</th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Time</th>
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

                <tbody class="divide-y divide-gray-200">
                    @forelse ($appointments as $appointment)
                        <tr class="hover:bg-gray-50" data-id="{{ $appointment->id }}"
                            data-status="{{ $appointment->status }}">

                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                {{ $appointment->patient->full_name ?? $appointment->first_name . ' ' . $appointment->last_name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $appointment->doctor->full_name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 hidden md:table-cell">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 hidden lg:table-cell">
                                {{ str_replace('_', ' ', ucfirst($appointment->appointment_type)) }}
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="status-badge inline-block px-2 py-1 text-xs rounded
                                @if ($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif ($appointment->status == 'confirmed') bg-blue-100 text-blue-700
                                @elseif ($appointment->status == 'completed') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if (in_array($appointment->status, ['completed', 'cancelled']))
                                    <span class="text-xs text-gray-400 italic">Closed</span>
                                @else
                                    <select class="status-select border rounded px-2 py-1 text-xs">
                                        <option value="">Change</option>

                                        @if ($appointment->status === 'pending')
                                            <option value="confirmed">Confirm</option>
                                            <option value="cancelled">Cancel</option>
                                        @endif

                                        @if ($appointment->status === 'confirmed')
                                            <option value="completed">Complete</option>
                                            <option value="cancelled">Cancel</option>
                                        @endif
                                    </select>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No appointments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div
            class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">

            {{-- Left info --}}
            <div class="text-xs sm:text-sm text-gray-600">
                Showing
                <span class="font-medium">{{ $appointments->firstItem() }}</span>
                to
                <span class="font-medium">{{ $appointments->lastItem() }}</span>
                of
                <span class="font-medium">{{ $appointments->total() }}</span>
                results
            </div>

            {{-- Pagination buttons --}}
            <div class="flex items-center gap-1">

                {{-- Previous --}}
                @if ($appointments->onFirstPage())
                    <span
                        class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <a href="{{ $appointments->previousPageUrl() }}"
                        class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg">
                        Previous
                    </a>
                @endif

                {{-- Page Numbers --}}
                @for ($page = 1; $page <= $appointments->lastPage(); $page++)
                    @if ($page == $appointments->currentPage())
                        <span class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-white bg-sky-600 rounded-lg">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $appointments->url($page) }}"
                            class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                {{-- Next --}}
                @if ($appointments->hasMorePages())
                    <a href="{{ $appointments->nextPageUrl() }}"
                        class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg">
                        Next
                    </a>
                @else
                    <span
                        class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        Next
                    </span>
                @endif

            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const filters = [
                '#filterSearch',
                '#filterDoctor',
                '#filterDate',
                '#filterStatus',
                '#filterType'
            ];

            filters.forEach(selector => {
                document.querySelector(selector).addEventListener('change', () => fetchData());
                document.querySelector(selector).addEventListener('keyup', () => fetchData());
            });

            function fetchData(pageUrl = null) {

                let params = new URLSearchParams({
                    search: document.getElementById('filterSearch').value,
                    doctor: document.getElementById('filterDoctor').value,
                    date: document.getElementById('filterDate').value,
                    status: document.getElementById('filterStatus').value,
                    type: document.getElementById('filterType').value,
                });

                let url = pageUrl ?
                    pageUrl :
                    `{{ route('frontdesk.appointments.index') }}?${params.toString()}`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Table + Pagination
                        document.querySelector('.bg-white.rounded-xl.shadow-sm.border-gray-200').innerHTML =
                            doc.querySelector('.bg-white.rounded-xl.shadow-sm.border-gray-200').innerHTML;

                        attachPagination();
                        attachStatusChange();
                    });
            }

            // PAGINATION AJAX (ACTIVE PAGE SAFE)
            function attachPagination() {
                document.querySelectorAll('a[href*="page="]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        fetchData(this.href);
                    });
                });
            }

            // STATUS CHANGE AJAX
            function attachStatusChange() {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.addEventListener('change', function() {

                        const row = this.closest('tr');
                        const id = row.dataset.id;
                        const newStatus = this.value;

                        if (!newStatus) return;

                        this.disabled = true;

                        fetch(`/frontdesk/appointments/${id}/status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    status: newStatus
                                })
                            })
                            .then(() => fetchData())
                            .catch(() => this.disabled = false);
                    });
                });
            }

            attachPagination();
            attachStatusChange();
        });
    </script>
@endpush
