@extends('layouts.doctor')

@section('title', 'My Schedule')

@section('page-title', 'My Schedule & Availability')

@section('content')
    <!-- Calendar Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-4 sm:mb-6">
        <div class="p-4 sm:p-6 border-b flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <button id="prevMonth" class="px-2 sm:px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h3 id="currentMonth" class="text-lg sm:text-xl font-semibold text-gray-800">
                    {{ date('F Y') }}
                </h3>
                <button id="nextMonth" class="px-2 sm:px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button id="viewMonth"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-sky-600 text-white rounded-lg text-xs sm:text-sm">
                    Month
                </button>
                <button id="viewWeek"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-xs sm:text-sm">
                    Week
                </button>
                <button id="viewDay"
                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-xs sm:text-sm">
                    Day
                </button>
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

            <!-- Calendar Days Container -->
            <div id="calendarDays" class="grid grid-cols-7 gap-1 sm:gap-2 md:gap-4">
                <div class="col-span-7 text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div>
                    <p class="mt-2 text-gray-500">Loading calendar...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Availability Settings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 sm:p-6 border-b flex justify-between items-center">
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Weekly Availability Schedule</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Your regular working hours for each day</p>
            </div>
            <a href="{{ route('admin.doctors.edit', auth()->id()) }}"
                class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm hover:bg-sky-700">
                Edit Schedule
            </a>
        </div>
        <div class="p-4 sm:p-6">
            <div id="weeklySchedule">
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div>
                    <p class="mt-2 text-gray-500">Loading schedule...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Info (Remove in production) -->
    <div id="debugInfo" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
        <h4 class="font-semibold text-yellow-800">Debug Information</h4>
        <pre id="debugContent" class="text-xs mt-2"></pre>
    </div>
@endsection

@push('scripts')
    <script>
        let currentMonth = '{{ date('Y-m') }}';
        let currentView = 'month'; // month, week, day
        let currentDate = new Date();

        $(document).ready(function() {
            console.log('Document ready, loading calendar for:', currentMonth);
            loadCalendar(currentMonth);
            loadWeeklySchedule();

            // View switchers
            $('#viewMonth').on('click', function() {
                switchView('month');
            });

            $('#viewWeek').on('click', function() {
                switchView('week');
            });

            $('#viewDay').on('click', function() {
                switchView('day');
            });

            $('#prevMonth').on('click', function() {
                if (currentView === 'month') {
                    const [year, month] = currentMonth.split('-');
                    const date = new Date(year, month - 1, 1);
                    date.setMonth(date.getMonth() - 1);
                    currentMonth = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
                    console.log('Loading previous month:', currentMonth);
                    loadCalendar(currentMonth);
                } else if (currentView === 'week') {
                    currentDate.setDate(currentDate.getDate() - 7);
                    loadWeekView();
                } else if (currentView === 'day') {
                    currentDate.setDate(currentDate.getDate() - 1);
                    loadDayView();
                }
            });

            $('#nextMonth').on('click', function() {
                if (currentView === 'month') {
                    const [year, month] = currentMonth.split('-');
                    const date = new Date(year, month - 1, 1);
                    date.setMonth(date.getMonth() + 1);
                    currentMonth = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
                    console.log('Loading next month:', currentMonth);
                    loadCalendar(currentMonth);
                } else if (currentView === 'week') {
                    currentDate.setDate(currentDate.getDate() + 7);
                    loadWeekView();
                } else if (currentView === 'day') {
                    currentDate.setDate(currentDate.getDate() + 1);
                    loadDayView();
                }
            });
        });

        function switchView(view) {
            currentView = view;

            // Update button styles
            $('#viewMonth, #viewWeek, #viewDay').removeClass('bg-sky-600 text-white').addClass(
                'border border-gray-300 text-gray-700');
            if (view === 'month') {
                $('#viewMonth').removeClass('border border-gray-300 text-gray-700').addClass('bg-sky-600 text-white');
                loadCalendar(currentMonth);
            } else if (view === 'week') {
                $('#viewWeek').removeClass('border border-gray-300 text-gray-700').addClass('bg-sky-600 text-white');
                loadWeekView();
            } else if (view === 'day') {
                $('#viewDay').removeClass('border border-gray-300 text-gray-700').addClass('bg-sky-600 text-white');
                loadDayView();
            }
        }

        function loadWeekView() {
            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());

            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);

            $('#currentMonth').text(
                `Week of ${startOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${endOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`
                );

            showLoading('#calendarDays', 'Loading week view...');

            // Load appointments for the week
            const weekDates = [];
            for (let i = 0; i < 7; i++) {
                const day = new Date(startOfWeek);
                day.setDate(startOfWeek.getDate() + i);
                weekDates.push(day.toISOString().split('T')[0]);
            }

            // For simplicity, we'll show a week grid
            let html = '';
            weekDates.forEach(function(dateStr) {
                const date = new Date(dateStr);
                const dayName = date.toLocaleDateString('en-US', {
                    weekday: 'short'
                });
                const dayNum = date.getDate();
                const isToday = dateStr === new Date().toISOString().split('T')[0];

                html +=
                    `<div class="col-span-1 border ${isToday ? 'border-sky-600 bg-sky-50' : 'border-gray-300'} rounded-lg p-2 min-h-[150px] cursor-pointer" data-date="${dateStr}">`;
                html +=
                    `<div class="text-center font-semibold ${isToday ? 'text-sky-700' : 'text-gray-800'}">${dayName}</div>`;
                html +=
                    `<div class="text-center text-2xl ${isToday ? 'text-sky-700' : 'text-gray-600'}">${dayNum}</div>`;
                html += `<div class="mt-2 text-xs text-gray-500 text-center">Click to view</div>`;
                html += `</div>`;
            });

            $('#calendarDays').html(html);

            $('#calendarDays > div').on('click', function() {
                const date = $(this).data('date');
                showDayAppointments(date);
            });
        }

        function loadDayView() {
            const dateStr = currentDate.toISOString().split('T')[0];
            $('#currentMonth').text(currentDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }));

            showLoading('#calendarDays', 'Loading day view...');

            $.ajax({
                url: '{{ route('doctor.calendar.appointments') }}',
                method: 'GET',
                data: {
                    date: dateStr
                },
                success: function(response) {
                    if (response.success) {
                        renderDayView(response.appointments, response.date);
                    } else {
                        showError('#calendarDays', 'Failed to load appointments');
                    }
                },
                error: function() {
                    showError('#calendarDays', 'Failed to load day view');
                }
            });
        }

        function renderDayView(appointments, dateTitle) {
            let html = '<div class="col-span-7">';
            html += `<h3 class="text-lg font-semibold mb-4 text-gray-800">${dateTitle}</h3>`;

            if (appointments && appointments.length > 0) {
                html += '<div class="space-y-3">';
                appointments.forEach(function(apt) {
                    let statusBadge = getStatusBadgeClass(apt.status);
                    html += `<div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">`;
                    html += `<div class="flex justify-between items-start">`;
                    html += `<div class="flex-1">`;
                    html += `<div class="flex items-center gap-3 mb-2">`;
                    html += `<p class="font-semibold text-lg">${apt.time}</p>`;
                    html += `<span class="px-3 py-1 text-xs ${statusBadge} rounded-full">${apt.status}</span>`;
                    html += `</div>`;
                    html +=
                        `<p class="font-medium text-gray-800">${apt.patient_name} <span class="text-sm text-gray-500">(${apt.patient_age} years)</span></p>`;
                    html += `<p class="text-sm text-gray-600 mt-1"><strong>Reason:</strong> ${apt.reason}</p>`;
                    html += `<p class="text-xs text-gray-400 mt-1">${apt.appointment_number}</p>`;
                    html += `</div>`;
                    html += `</div>`;
                    html += `</div>`;
                });
                html += '</div>';
            } else {
                html += '<div class="text-center py-12">';
                html +=
                    '<svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                html +=
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
                html += '</svg>';
                html += '<p class="text-gray-500">No appointments scheduled for this day</p>';
                html += '</div>';
            }

            html += '</div>';
            $('#calendarDays').html(html);
        }

        function loadCalendar(month) {
            console.log('Loading calendar for month:', month);

            showLoading('#calendarDays', 'Loading calendar...');

            $.ajax({
                url: '{{ route('doctor.calendar.data') }}',
                method: 'GET',
                data: {
                    month: month
                },
                success: function(response) {
                    console.log('Calendar response:', response);

                    if (response.success) {
                        $('#currentMonth').text(response.data.month_name);
                        renderCalendar(response.data.days);
                    } else {
                        showError('#calendarDays', response.message || 'Failed to load calendar');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Calendar AJAX error:', error);
                    console.log('XHR response:', xhr.responseText);
                    showError('#calendarDays', 'Failed to load calendar. Please check console for details.');
                }
            });
        }

        function renderCalendar(days) {
            console.log('Rendering calendar days:', days);

            if (!days || !Array.isArray(days)) {
                showError('#calendarDays', 'Invalid calendar data received');
                return;
            }

            let html = '';

            days.forEach(function(day) {
                const isCurrentMonth = day.is_current_month;
                const isToday = day.is_today;
                const hasAppointments = day.appointments && day.appointments.length > 0;

                let classes = 'aspect-square border rounded-lg p-1 sm:p-2 min-h-[60px] sm:min-h-[80px]';

                if (isCurrentMonth) {
                    classes += ' cursor-pointer';
                }

                if (!isCurrentMonth) {
                    classes += ' bg-gray-50 border-gray-200';
                } else if (isToday) {
                    classes += ' border-2 border-sky-600 bg-sky-50';
                } else {
                    classes += ' border-gray-300 hover:border-gray-400';
                }

                html += `<div class="${classes}" data-date="${day.date}">`;
                html +=
                    `<div class="text-xs sm:text-sm font-medium ${isCurrentMonth ? (isToday ? 'text-sky-700 font-bold' : 'text-gray-800') : 'text-gray-400'}">${day.day}</div>`;

                if (hasAppointments && isCurrentMonth) {
                    html += '<div class="mt-1 space-y-0.5 sm:space-y-1">';
                    day.appointments.slice(0, 2).forEach(function(apt) {
                        let badgeClass = 'bg-gray-100 text-gray-700';
                        if (apt.status === 'completed') {
                            badgeClass = 'bg-emerald-100 text-emerald-700';
                        } else if (apt.status === 'confirmed' || apt.status === 'checked_in' || apt
                            .status === 'in_progress') {
                            badgeClass = 'bg-sky-100 text-sky-700';
                        } else if (apt.status === 'pending') {
                            badgeClass = 'bg-amber-100 text-amber-700';
                        }
                        html +=
                            `<div class="text-xs ${badgeClass} px-1 py-0.5 rounded truncate hidden sm:block">${apt.time}</div>`;
                    });
                    if (day.appointments.length > 2) {
                        html +=
                            `<div class="text-xs text-gray-500 px-1 hidden sm:block">+${day.appointments.length - 2} more</div>`;
                    }
                    html += '</div>';
                }

                html += '</div>';
            });

            $('#calendarDays').html(html);

            // Add click handlers
            $('#calendarDays > div').on('click', function() {
                const date = $(this).data('date');
                const isCurrentMonth = !$(this).hasClass('bg-gray-50');

                if (date && isCurrentMonth) {
                    showDayAppointments(date);
                }
            });
        }

        function loadWeeklySchedule() {
            console.log('Loading weekly schedule...');

            $.ajax({
                url: '{{ route('doctor.calendar.schedule') }}',
                method: 'GET',
                success: function(response) {
                    console.log('Schedule response:', response);

                    if (response.success) {
                        renderWeeklySchedule(response.schedule);
                    } else {
                        showError('#weeklySchedule', response.message || 'Failed to load schedule');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Schedule AJAX error:', error);
                    showError('#weeklySchedule', 'Failed to load schedule');
                }
            });
        }

        function renderWeeklySchedule(schedule) {
            console.log('Rendering schedule:', schedule);

            if (!schedule || !Array.isArray(schedule)) {
                showError('#weeklySchedule', 'Invalid schedule data');
                return;
            }

            let html = '';

            schedule.forEach(function(day) {
                const isAvailable = day.is_available;
                const bgClass = isAvailable ? '' : 'bg-gray-50';

                html +=
                    `<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg ${bgClass} mb-3">`;
                html += `<div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">`;
                html += `<div class="flex items-center gap-3 sm:gap-4">`;
                html +=
                    `<span class="font-medium text-gray-800 text-sm sm:text-base sm:w-24">${day.day_name}</span>`;
                html += `</div>`;

                if (isAvailable) {
                    html += `<div class="flex items-center gap-2 flex-1">`;
                    html += `<span class="text-sm">${day.start_time}</span>`;
                    html += `<span class="text-gray-500 text-xs sm:text-sm">to</span>`;
                    html += `<span class="text-sm">${day.end_time}</span>`;
                    if (day.slot_duration) {
                        html += `<span class="text-xs text-gray-500 ml-2">(${day.slot_duration} min slots)</span>`;
                    }
                    html += `</div>`;
                } else {
                    html += `<span class="text-sm text-gray-500">Not Available</span>`;
                }

                html += `</div>`;
                html += `</div>`;
            });

            $('#weeklySchedule').html(html);
        }

        function showDayAppointments(date) {
            console.log('Loading appointments for:', date);

            $.ajax({
                url: '{{ route('doctor.calendar.appointments') }}',
                method: 'GET',
                data: {
                    date: date
                },
                success: function(response) {
                    console.log('Appointments response:', response);

                    if (response.success) {
                        showAppointmentsModal(response);
                    } else {
                        alert('Failed to load appointments: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Appointments AJAX error:', error);
                    alert('Failed to load appointments. Please try again.');
                }
            });
        }

        function showAppointmentsModal(data) {
            const modalHtml = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h3 class="text-lg font-semibold">Appointments for ${data.date}</h3>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        ${data.appointments && data.appointments.length > 0 ? 
                            data.appointments.map(apt => `
                                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-medium">${apt.patient_name}</p>
                                                <p class="text-sm text-gray-500">${apt.patient_age} years</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-sky-600">${apt.time}</p>
                                                <span class="px-2 py-1 text-xs ${getStatusBadgeClass(apt.status)} rounded-full">
                                                    ${apt.status}
                                                </span>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600"><strong>Reason:</strong> ${apt.reason}</p>
                                        <p class="text-xs text-gray-400 mt-1">${apt.appointment_number}</p>
                                    </div>
                                `).join('') : 
                            '<p class="text-center text-gray-500 py-8">No appointments scheduled for this date</p>'
                        }
                    </div>
                </div>
            </div>
        `;

            $('body').append(modalHtml);
        }

        function getStatusBadgeClass(status) {
            switch (status) {
                case 'completed':
                    return 'bg-emerald-100 text-emerald-700';
                case 'confirmed':
                    return 'bg-sky-100 text-sky-700';
                case 'pending':
                    return 'bg-amber-100 text-amber-700';
                default:
                    return 'bg-gray-100 text-gray-700';
            }
        }

        function closeModal() {
            $('.fixed.inset-0').remove();
        }

        function showLoading(selector, message) {
            $(selector).html(`
            <div class="col-span-7 text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div>
                <p class="mt-2 text-gray-500">${message}</p>
            </div>
        `);
        }

        function showError(selector, message) {
            $(selector).html(`
            <div class="col-span-7 text-center py-8">
                <div class="text-red-500 mb-2">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <p class="text-gray-600">${message}</p>
                <button onclick="location.reload()" class="mt-3 px-4 py-2 bg-sky-600 text-white rounded-lg text-sm hover:bg-sky-700">
                    Retry
                </button>
            </div>
        `);
        }

        // Close modal when clicking outside
        $(document).on('click', function(event) {
            if ($(event.target).hasClass('fixed') && $(event.target).hasClass('inset-0')) {
                closeModal();
            }
        });
    </script>
@endpush
