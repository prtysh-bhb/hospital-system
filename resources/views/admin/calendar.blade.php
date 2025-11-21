@extends('layouts.admin')

@section('title', 'Calendar')

@section('page-title', 'Appointments Calendar')

@section('header-actions')
    <form method="GET" action="{{ route('admin.calendar') }}" class="flex items-center gap-3">
        <select name="doctor_id" class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500"
            onchange="this.form.submit()">
            <option value="">All Doctors</option>
            @foreach ($doctors as $doctor)
                <option value="{{ $doctor['id'] }}" {{ $doctorId == $doctor['id'] ? 'selected' : '' }}>
                    {{ $doctor['name'] }} - {{ $doctor['specialty'] }}
                </option>
            @endforeach
        </select>
        <input type="hidden" name="year" value="{{ $calendarData['year'] }}">
        <input type="hidden" name="month" value="{{ $calendarData['month'] }}">
    </form>
    <a href="{{ route('admin.add-appointment') }}"
        class="px-6 py-2 text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">+ New Appointment</a>
@endsection

@section('content')
    <!-- Calendar Controls -->
    <div class="bg-white p-3 sm:p-4 rounded-lg sm:rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">
            <div class="flex items-center space-x-2 sm:space-x-4">
                <a href="{{ route('admin.calendar', ['year' => explode('-', $calendarData['prev_month'])[0], 'month' => explode('-', $calendarData['prev_month'])[1], 'doctor_id' => $doctorId]) }}"
                    class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">{{ $calendarData['month_name'] }}</h3>
                <a href="{{ route('admin.calendar', ['year' => explode('-', $calendarData['next_month'])[0], 'month' => explode('-', $calendarData['next_month'])[1], 'doctor_id' => $doctorId]) }}"
                    class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="flex gap-2">
                <button
                    class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-white bg-sky-600 rounded-lg">Month</button>
                <button
                    class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Week</button>
                <button
                    class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Day</button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase">Total</p>
            <p class="text-2xl font-bold text-gray-800">{{ $statistics['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase">Confirmed</p>
            <p class="text-2xl font-bold text-green-600">{{ $statistics['confirmed'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase">Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $statistics['pending'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase">Completed</p>
            <p class="text-2xl font-bold text-sky-600">{{ $statistics['completed'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase">Cancelled</p>
            <p class="text-2xl font-bold text-red-600">{{ $statistics['cancelled'] }}</p>
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
            @foreach ($calendarData['days'] as $day)
                @php
                    $colors = ['sky', 'purple', 'emerald', 'amber', 'rose', 'indigo', 'pink'];
                    $borderClass = in_array($loop->index % 7, [0, 1, 2, 3, 4, 5]) ? 'border-r' : '';
                    $bgClass = !$day['is_current_month'] ? 'bg-gray-50' : '';
                    $todayBg = $day['is_today'] ? 'bg-sky-50' : '';
                @endphp
                <div
                    class="p-4 {{ $borderClass }} border-b border-gray-100 h-32 {{ $bgClass }} {{ $todayBg }}">
                    <p
                        class="text-sm font-semibold mb-2 {{ !$day['is_current_month'] ? 'text-gray-400' : 'text-gray-800' }} {{ $day['is_today'] ? 'text-sky-700' : '' }}">
                        {{ $day['day'] }}
                        @if ($day['is_today'])
                            <span class="text-xs"> • Today</span>
                        @endif
                    </p>
                    <div class="space-y-1">
                        @foreach ($day['appointments'] as $index => $appointment)
                            @if ($index < 2)
                                @php
                                    $color = $colors[$index % count($colors)];
                                    $statusColors = [
                                        'confirmed' => 'emerald',
                                        'pending' => 'amber',
                                        'completed' => 'sky',
                                        'cancelled' => 'red',
                                    ];
                                    $statusColor = $statusColors[$appointment['status']] ?? 'gray';
                                @endphp
                                <div class="text-xs px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 rounded truncate cursor-pointer hover:bg-{{ $statusColor }}-200"
                                    onclick="showAppointmentDetails({{ $appointment['id'] }})"
                                    title="{{ $appointment['time'] }} - {{ $appointment['patient_name'] }} with {{ $appointment['doctor_name'] }}">
                                    {{ $appointment['time'] }} {{ $appointment['doctor_short'] }}
                                </div>
                            @endif
                        @endforeach
                        @if (count($day['appointments']) > 2)
                            <div class="text-xs text-sky-600 px-2 cursor-pointer hover:text-sky-800 font-medium"
                                onclick="showDateAppointments('{{ $day['date'] }}')"
                                title="Click to see all appointments">
                                +{{ count($day['appointments']) - 2 }} more
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Appointment Details Modal -->
    <div id="appointmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center h-full p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800">Appointment Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="p-6">
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-sky-600"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Appointments Modal -->
    <div id="dateAppointmentsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center h-full p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800" id="dateModalTitle">Appointments</h3>
                    <button onclick="closeDateModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div id="dateModalContent" class="p-6">
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-sky-600"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showAppointmentDetails(appointmentId) {
            const modal = document.getElementById('appointmentModal');
            const modalContent = document.getElementById('modalContent');

            modal.classList.remove('hidden');

            modalContent.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-sky-600"></div>
                </div>
            `;

            fetch(`/admin/appointments/${appointmentId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const apt = data.appointment;
                        const statusColors = {
                            'confirmed': 'bg-green-100 text-green-800',
                            'pending': 'bg-amber-100 text-amber-800',
                            'completed': 'bg-sky-100 text-sky-800',
                            'cancelled': 'bg-red-100 text-red-800'
                        };

                        modalContent.innerHTML = `
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">#${apt.appointment_number}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusColors[apt.status] || 'bg-gray-100 text-gray-800'}">
                                        ${apt.status.toUpperCase()}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase mb-1">Date</p>
                                        <p class="text-sm font-semibold text-gray-800">${apt.formatted_date}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase mb-1">Time</p>
                                        <p class="text-sm font-semibold text-gray-800">${apt.formatted_time}</p>
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Patient Information
                                    </h4>
                                    <div class="space-y-2 pl-7">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Name:</span>
                                            <span class="text-sm font-medium text-gray-800">${apt.patient_name}</span>
                                        </div>
                                        ${apt.patient_phone ? `
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-600">Phone:</span>
                                                    <span class="text-sm font-medium text-gray-800">${apt.patient_phone}</span>
                                                </div>
                                                ` : ''}
                                        ${apt.patient_email ? `
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-600">Email:</span>
                                                    <span class="text-sm font-medium text-gray-800">${apt.patient_email}</span>
                                                </div>
                                                ` : ''}
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Doctor Information
                                    </h4>
                                    <div class="space-y-2 pl-7">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Name:</span>
                                            <span class="text-sm font-medium text-gray-800">${apt.doctor_name}</span>
                                        </div>
                                        ${apt.doctor_specialty ? `
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-600">Specialty:</span>
                                                    <span class="text-sm font-medium text-gray-800">${apt.doctor_specialty}</span>
                                                </div>
                                                ` : ''}
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Appointment Details</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Type:</span>
                                            <span class="text-sm font-medium text-gray-800">${apt.appointment_type.replace('_', ' ').toUpperCase()}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Duration:</span>
                                            <span class="text-sm font-medium text-gray-800">${apt.duration_minutes} minutes</span>
                                        </div>
                                        ${apt.reason_for_visit ? `
                                                <div>
                                                    <span class="text-sm text-gray-600">Reason:</span>
                                                    <p class="text-sm font-medium text-gray-800 mt-1">${apt.reason_for_visit}</p>
                                                </div>
                                                ` : ''}
                                        ${apt.symptoms ? `
                                                <div>
                                                    <span class="text-sm text-gray-600">Symptoms:</span>
                                                    <p class="text-sm font-medium text-gray-800 mt-1">${apt.symptoms}</p>
                                                </div>
                                                ` : ''}
                                        ${apt.notes ? `
                                                <div>
                                                    <span class="text-sm text-gray-600">Notes:</span>
                                                    <p class="text-sm font-medium text-gray-800 mt-1">${apt.notes}</p>
                                                </div>
                                                ` : ''}
                                    </div>
                                </div>
                                
                                <div class="border-t pt-4">
                                    <button onclick="closeModal()" class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm font-medium">
                                        Close
                                    </button>
                                </div>
                            </div>
                        `;
                    } else {
                        modalContent.innerHTML = `
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-600">Failed to load appointment details.</p>
                                <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    Close
                                </button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = `
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-600">An error occurred while loading the appointment.</p>
                            <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                Close
                            </button>
                        </div>
                    `;
                });
        }

        function closeModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }

        function showDateAppointments(date) {
            const modal = document.getElementById('dateAppointmentsModal');
            const modalContent = document.getElementById('dateModalContent');
            const modalTitle = document.getElementById('dateModalTitle');

            modal.classList.remove('hidden');

            modalContent.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-sky-600"></div>
                </div>
            `;

            const doctorId = '{{ $doctorId ?? '' }}';
            const url = `/admin/calendar/appointments?date=${date}${doctorId ? '&doctor_id=' + doctorId : ''}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalTitle.textContent = `Appointments for ${data.date}`;

                        if (data.appointments.length === 0) {
                            modalContent.innerHTML = `
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-600">No appointments found for this date.</p>
                                </div>
                            `;
                        } else {
                            const statusColors = {
                                'confirmed': 'bg-green-100 text-green-800 border-green-200',
                                'pending': 'bg-amber-100 text-amber-800 border-amber-200',
                                'completed': 'bg-sky-100 text-sky-800 border-sky-200',
                                'cancelled': 'bg-red-100 text-red-800 border-red-200'
                            };

                            const appointmentsHtml = data.appointments.map(apt => `
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition cursor-pointer" onclick="closeDateModal(); showAppointmentDetails(${apt.id})">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">${apt.time}</p>
                                            <p class="text-xs text-gray-500">#${apt.appointment_number}</p>
                                        </div>
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold ${statusColors[apt.status] || 'bg-gray-100 text-gray-800 border-gray-200'} border">
                                            ${apt.status.toUpperCase()}
                                        </span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="text-gray-700 font-medium">${apt.patient_name}</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-gray-700">${apt.doctor_name}</span>
                                            ${apt.specialty ? `<span class="text-gray-500 ml-1">(${apt.specialty})</span>` : ''}
                                        </div>
                                        ${apt.reason ? `
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 mr-2 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-gray-600">${apt.reason}</span>
                                            </div>
                                            ` : ''}
                                    </div>
                                </div>
                            `).join('');

                            modalContent.innerHTML = `
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    ${appointmentsHtml}
                                </div>
                            `;
                        }
                    } else {
                        modalContent.innerHTML = `
                            <div class="text-center py-8">
                                <p class="text-red-600">Failed to load appointments.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-red-600">An error occurred while loading appointments.</p>
                        </div>
                    `;
                });
        }

        function closeDateModal() {
            document.getElementById('dateAppointmentsModal').classList.add('hidden');
        }

        document.getElementById('appointmentModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('dateAppointmentsModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDateModal();
            }
        });
    </script>
@endsection
