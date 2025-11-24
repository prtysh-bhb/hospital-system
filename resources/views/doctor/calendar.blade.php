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
        <div class="p-4 sm:p-6 border-b">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Weekly Availability Schedule</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Set your regular working hours for each day</p>
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

    <!-- Appointment Details Modal -->
    <div id="appointmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center h-full p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800">Appointment Details</h3>
                    <button onclick="closeAppointmentModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div id="appointmentModalContent" class="p-6">
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-sky-600"></div>
                    </div>
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

            // Fetch appointments for all days in the week
            Promise.all(weekDates.map(date =>
                fetch(`{{ route('doctor.calendar.appointments') }}?date=${date}`)
                .then(response => response.json())
                .catch(() => ({
                    success: false,
                    appointments: []
                }))
            )).then(responses => {
                renderWeekView(weekDates, responses);
            });
        }

        function renderWeekView(weekDates, responses) {
            const statusColors = {
                'completed': 'bg-emerald-100 text-emerald-700',
                'confirmed': 'bg-sky-100 text-sky-700',
                'pending': 'bg-amber-100 text-amber-700',
                'cancelled': 'bg-red-100 text-red-700'
            };

            let html = '';
            weekDates.forEach(function(dateStr, index) {
                const date = new Date(dateStr);
                const dayName = date.toLocaleDateString('en-US', {
                    weekday: 'short'
                });
                const dayNum = date.getDate();
                const isToday = dateStr === new Date().toISOString().split('T')[0];
                const appointments = responses[index]?.success ? responses[index].appointments : [];

                html +=
                    `<div class="col-span-1 border ${isToday ? 'border-2 border-sky-600 bg-sky-50' : 'border-gray-300'} rounded-lg p-2 min-h-[200px]" data-date="${dateStr}">`;
                html +=
                    `<div class="text-center font-semibold ${isToday ? 'text-sky-700' : 'text-gray-800'} mb-2">${dayName}</div>`;
                html +=
                    `<div class="text-center text-2xl ${isToday ? 'text-sky-700' : 'text-gray-600'} mb-3">${dayNum}</div>`;

                if (appointments.length > 0) {
                    html += '<div class="space-y-2">';
                    appointments.forEach(apt => {
                        const statusClass = statusColors[apt.status] || 'bg-gray-100 text-gray-700';
                        html += `<div class="text-xs px-2 py-1.5 ${statusClass} rounded cursor-pointer hover:shadow transition" 
                                    onclick="showAppointmentDetailsById(${apt.id})" 
                                    title="${apt.patient_name} - ${apt.reason}">`;
                        html += `<div class="font-semibold">${apt.time}</div>`;
                        html += `<div class="truncate">${apt.patient_name}</div>`;
                        html += `</div>`;
                    });
                    html += '</div>';
                } else {
                    html += '<p class="text-xs text-gray-400 text-center mt-2">No appointments</p>';
                }

                html += `</div>`;
            });

            $('#calendarDays').html(html);
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
                    html +=
                        `<div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" onclick="showAppointmentDetailsById(${apt.id})">`;
                    html += `<div class="flex justify-between items-start">`;
                    html += `<div class="flex-1">`;
                    html += `<div class="flex items-center gap-3 mb-2">`;
                    html += `<p class="font-semibold text-lg">${apt.time}</p>`;
                    html +=
                        `<span class="px-3 py-1 text-xs ${statusBadge} rounded-full">${apt.status.toUpperCase()}</span>`;
                    html += `</div>`;
                    html +=
                        `<p class="font-medium text-gray-800">${apt.patient_name} <span class="text-sm text-gray-500">(${apt.patient_age} years)</span></p>`;
                    html += `<p class="text-sm text-gray-600 mt-1"><strong>Type:</strong> ${apt.type}</p>`;
                    html += `<p class="text-sm text-gray-600"><strong>Reason:</strong> ${apt.reason}</p>`;
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

            let html = '<div class="space-y-3 sm:space-y-4">';

            schedule.forEach(function(day) {
                const isAvailable = day.is_available;
                const bgClass = isAvailable ? '' : 'bg-gray-50';
                const dayNum = day.day_of_week;

                html +=
                    `<div class="schedule-day flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg ${bgClass}" data-day="${dayNum}">`;
                html += `<div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 flex-1">`;
                html += `<div class="flex items-center gap-3 sm:gap-4">`;
                html +=
                    `<input type="checkbox" class="day-checkbox w-4 h-4 sm:w-5 sm:h-5 text-sky-600 rounded" ${isAvailable ? 'checked' : ''}>`;
                html +=
                    `<span class="font-medium ${isAvailable ? 'text-gray-800' : 'text-gray-500'} text-sm sm:text-base sm:w-24">${day.day_name}</span>`;
                html += `</div>`;

                if (isAvailable) {
                    // Convert time format (e.g., "9:00 AM" to "09:00")
                    const startTime = convertTo24Hour(day.start_time);
                    const endTime = convertTo24Hour(day.end_time);

                    html += `<div class="flex items-center gap-2 flex-1">`;
                    html +=
                        `<input type="time" class="start-time px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none" value="${startTime}">`;
                    html += `<span class="text-gray-500 text-xs sm:text-sm">to</span>`;
                    html +=
                        `<input type="time" class="end-time px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none" value="${endTime}">`;
                    html += `</div>`;
                } else {
                    html += `<span class="text-gray-400 text-sm sm:text-base unavailable-text">Unavailable</span>`;
                }

                html += `</div>`;
                html += `</div>`;
            });

            html += '</div>';

            html += `
            <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-end gap-3">
                <button id="cancelSchedule" class="px-4 sm:px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm sm:text-base">
                    Cancel
                </button>
                <button id="saveSchedule" class="px-4 sm:px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm sm:text-base">
                    Save Changes
                </button>
            </div>
        `;

            $('#weeklySchedule').html(html);

            // Attach event handlers
            attachScheduleHandlers();
        }

        function convertTo24Hour(time12h) {
            if (!time12h) return '09:00';

            const [time, modifier] = time12h.split(' ');
            let [hours, minutes] = time.split(':');

            hours = parseInt(hours, 10);

            if (modifier === 'PM' && hours !== 12) {
                hours = hours + 12;
            } else if (modifier === 'AM' && hours === 12) {
                hours = 0;
            }

            return `${String(hours).padStart(2, '0')}:${minutes}`;
        }

        function attachScheduleHandlers() {
            // Handle checkbox changes
            $('.day-checkbox').on('change', function() {
                const $dayRow = $(this).closest('.schedule-day');
                const isChecked = $(this).is(':checked');
                const $container = $dayRow.find('.flex-col').first();

                if (isChecked) {
                    // Enable the day
                    $dayRow.removeClass('bg-gray-50');
                    $container.find('span').first().removeClass('text-gray-500').addClass('text-gray-800');

                    // Remove unavailable text
                    $container.find('.unavailable-text').remove();

                    // Add time inputs
                    const timeInputsHtml = `
                        <div class="flex items-center gap-2 flex-1">
                            <input type="time" class="start-time px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none" value="09:00">
                            <span class="text-gray-500 text-xs sm:text-sm">to</span>
                            <input type="time" class="end-time px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm sm:text-base flex-1 sm:flex-none" value="17:00">
                        </div>
                    `;
                    $container.append(timeInputsHtml);
                } else {
                    // Disable the day
                    $dayRow.addClass('bg-gray-50');
                    $container.find('span').first().removeClass('text-gray-800').addClass('text-gray-500');

                    // Remove time inputs div
                    $container.find('.flex.items-center.gap-2').remove();

                    // Add unavailable text
                    $container.append(
                        '<span class="text-gray-400 text-sm sm:text-base unavailable-text">Unavailable</span>');
                }
            });

            // Save schedule
            $('#saveSchedule').on('click', function() {
                saveSchedule();
            });

            // Cancel changes
            $('#cancelSchedule').on('click', function() {
                loadWeeklySchedule();
            });
        }

        function saveSchedule() {
            const schedules = [];
            let hasError = false;

            $('.schedule-day').each(function() {
                const dayNum = parseInt($(this).data('day'));
                const isAvailable = $(this).find('.day-checkbox').is(':checked');
                const startTime = $(this).find('.start-time').val();
                const endTime = $(this).find('.end-time').val();

                if (isAvailable && (!startTime || !endTime)) {
                    alert('Please set both start and end times for all available days');
                    hasError = true;
                    return false;
                }

                if (isAvailable && startTime >= endTime) {
                    alert('End time must be after start time for all days');
                    hasError = true;
                    return false;
                }

                schedules.push({
                    day_of_week: dayNum,
                    is_available: isAvailable ? true : false,
                    start_time: startTime || null,
                    end_time: endTime || null,
                    slot_duration: 30
                });
            });

            if (hasError) return;

            console.log('Sending schedules:', schedules);

            // Show loading
            $('#saveSchedule').prop('disabled', true).html(
                '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Saving...'
            );

            $.ajax({
                url: '{{ route('doctor.calendar.schedule.update') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    schedules: schedules
                }),
                success: function(response) {
                    console.log('Save response:', response);
                    if (response.success) {
                        // Show success message
                        const successMsg = $(
                            '<div class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">Schedule updated successfully!</div>'
                        );
                        $('body').append(successMsg);
                        setTimeout(function() {
                            successMsg.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 3000);

                        // Reload schedule
                        renderWeeklySchedule(response.schedule);
                    } else {
                        alert('Failed to update schedule: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr) {
                    console.error('Schedule update error:', xhr);
                    console.error('Response:', xhr.responseText);
                    let errorMsg = 'Failed to update schedule. Please try again.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    alert(errorMsg);
                },
                complete: function() {
                    $('#saveSchedule').prop('disabled', false).html('Save Changes');
                }
            });
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

        // Show appointment details by ID
        function showAppointmentDetailsById(appointmentId) {
            const modal = document.getElementById('appointmentModal');
            const modalContent = document.getElementById('appointmentModalContent');

            modal.classList.remove('hidden');

            modalContent.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-sky-600"></div>
                </div>
            `;

            fetch(`{{ route('doctor.appointment-details', ['id' => ':id']) }}`.replace(':id', appointmentId) +
                    '/details-json')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200) {
                        renderAppointmentDetails(data.data);
                    } else {
                        showAppointmentError('Failed to load appointment details.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAppointmentError('An error occurred while loading the appointment.');
                });
        }

        function renderAppointmentDetails(data) {
            const apt = data.appointment;
            const patient = data.patient;

            const statusColors = {
                'confirmed': 'bg-green-100 text-green-800',
                'pending': 'bg-amber-100 text-amber-800',
                'completed': 'bg-sky-100 text-sky-800',
                'cancelled': 'bg-red-100 text-red-800'
            };

            const modalContent = document.getElementById('appointmentModalContent');
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
                            <p class="text-sm font-semibold text-gray-800">${apt.date}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Time</p>
                            <p class="text-sm font-semibold text-gray-800">${apt.time}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Type</p>
                            <p class="text-sm font-semibold text-gray-800">${apt.type}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Duration</p>
                            <p class="text-sm font-semibold text-gray-800">${apt.duration} minutes</p>
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
                            <p class="text-sm"><span class="font-medium text-gray-700">Name:</span> ${patient.name}</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">Age:</span> ${patient.age || 'N/A'} years</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">Gender:</span> ${patient.gender}</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">Phone:</span> ${patient.phone || 'N/A'}</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">Email:</span> ${patient.email || 'N/A'}</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Appointment Details</h4>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium text-gray-700">Reason:</span> ${apt.reason || 'Not specified'}</p>
                            ${apt.symptoms ? `<p class="text-sm"><span class="font-medium text-gray-700">Symptoms:</span> ${apt.symptoms}</p>` : ''}
                            ${apt.notes ? `<p class="text-sm"><span class="font-medium text-gray-700">Notes:</span> ${apt.notes}</p>` : ''}
                        </div>
                    </div>
                    
                    <div class="border-t pt-4 flex gap-3">
                        <a href="{{ route('doctor.appointment-details', ['id' => ':id']) }}".replace(':id', apt.id) 
                           class="flex-1 px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm font-medium text-center">
                            View Full Details
                        </a>
                        <button onclick="closeAppointmentModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">
                            Close
                        </button>
                    </div>
                </div>
            `;
        }

        function showAppointmentError(message) {
            const modalContent = document.getElementById('appointmentModalContent');
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600">${message}</p>
                    <button onclick="closeAppointmentModal()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Close
                    </button>
                </div>
            `;
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('appointmentModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAppointmentModal();
            }
        });

        $(document).on('click', function(event) {
            if ($(event.target).hasClass('fixed') && $(event.target).hasClass('inset-0')) {
                closeModal();
            }
        });
    </script>
@endpush
