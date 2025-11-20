@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500">Total Patients</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2"><span id="totalPatientsCount"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            {{-- <p class="text-sm text-green-600 mt-4">↑ 12% from last month</p> --}}
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500">Today's Appointments</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2"><span id="todaysAppointmentsCount"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            {{-- <p class="text-sm text-gray-600 mt-4">8 pending confirmation</p> --}}
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500">Total Doctors</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2"><span id="totalDoctorsCount"></span></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            {{-- <p class="text-sm text-green-600 mt-4">24 active today</p> --}}
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500">Revenue (Today)</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2"><span id="revenueToday"></span></p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            {{-- <p class="text-sm text-green-600 mt-4">↑ 8% from yesterday</p> --}}
        </div>
    </div>

    <!-- Recent Appointments & Active Doctors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 sm:p-6 border-b border-gray-100">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recent Appointments</h3>
            </div>
            <div class="p-4 sm:p-6">
                <div id="recentAppointmentsContainer" class="space-y-4 max-h-64 overflow-y-auto">

                </div>
            </div>
        </div>

        <!-- Active Doctors -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 sm:p-6 border-b border-gray-100">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Active Doctors Today</h3>
            </div>
            <div class="p-4 sm:p-6">
                <div id="activeDoctorsContainer" class="space-y-4 max-h-64 overflow-y-auto">

                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recent Activity</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div id="recentActivityContainer" class="space-y-6 max-h-64 overflow-y-auto">

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // Load dashboard data and replace the sample items with dynamic content.
            function renderRecentAppointments(list) {
                if (!Array.isArray(list) || list.length === 0) return;
                var html = '';
                list.forEach(function(a, idx) {
                    // create initials for avatar when no image available
                    var initials = (a.patient_name || '—').split(' ').map(function(s) {
                        return s.charAt(0)
                    }).slice(0, 2).join('').toUpperCase();
                    var colorClass = 'bg-sky-100 text-sky-600';
                    var item = '' +
                        '<div class="flex items-center justify-between ' + (idx < list.length - 1 ?
                            'pb-4 border-b border-gray-100' : '') + '">' +
                        '<div class="flex items-center">' +
                        '<div class="w-10 h-10 ' + colorClass +
                        ' rounded-full flex items-center justify-center font-semibold">' + initials + '</div>' +
                        '<div class="ml-3">' +
                        '<p class="text-sm font-medium text-gray-800">' + (a.patient_name || '—') + '</p>' +
                        '<p class="text-xs text-gray-500">' + (a.doctor_name ? a.doctor_name + ' • ' : '') + (a
                            .date || '') + (a.time ? ' ' + a.time : '') + '</p>' +
                        '</div></div>' +
                        '<span class="px-3 py-1 text-xs font-medium ' + (a.status_color ||
                            'text-gray-700 bg-gray-100') + ' rounded-full">' + (a.status_label || '') +
                        '</span>' +
                        '</div>';
                    html += item;
                });
                $('#recentAppointmentsContainer').html(html);
            }

            function renderActiveDoctors(list) {
                if (!Array.isArray(list) || list.length === 0) return;
                var html = '';
                list.forEach(function(d, idx) {
                    var avatar = d.avatar || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(d.name ||
                        '') + '&background=8b5cf6&color=fff');
                    var item = '' +
                        '<div class="flex items-center justify-between ' + (idx < list.length - 1 ?
                            'pb-4 border-b border-gray-100' : '') + '">' +
                        '<div class="flex items-center">' +
                        '<img src="' + avatar + '" class="w-12 h-12 rounded-full" alt="Doctor">' +
                        '<div class="ml-3">' +
                        '<p class="text-sm font-medium text-gray-800">' + (d.name || '—') + '</p>' +
                        '<p class="text-xs text-gray-500">' + (d.specialty || '') + '</p>' +
                        '</div></div>' +
                        '<div class="text-right">' +
                        '<p class="text-sm font-semibold text-gray-800">' + (d.totalAppointments || 0) +
                        ' patients</p>' +
                        '<p class="text-xs text-gray-500">' + (d.timing || '') + '</p>' +
                        '</div>' +
                        '</div>';
                    html += item;
                });
                $('#activeDoctorsContainer').html(html);
            }

            function renderRecentActivity(list) {
                if (!Array.isArray(list) || list.length === 0) return;
                var html = '';
                list.forEach(function(it, idx) {
                    html += '' +
                        '<div class="flex ' + (idx < list.length - 1 ? '' : '') + '">' +
                        '<div class="shrink-0">' +
                        '<div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">' +
                        '<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />' +
                        '</svg></div></div>' +
                        '<div class="ml-4">' +
                        '<p class="text-sm text-gray-800"><span class="font-semibold">' + (it.title || '') +
                        '</span></p>' +
                        '<p class="text-xs text-gray-500 mt-1">' + (it.time || '') + '</p>' +
                        '</div></div>';
                });
                $('#recentActivityContainer').html(html);
            }

            function loadStats() {
                $.ajax({
                    url: "{{ route('admin.dashboard.details') }}",
                    method: 'GET',
                    success: function(res) {
                        // update metric numbers but keep original markup/classes
                        $('#totalPatientsCount').text(res.totalPatients ?? $('#totalPatientsCount').text());
                        $('#todaysAppointmentsCount').text(res.todaysAppointments ?? $(
                            '#todaysAppointmentsCount').text());
                        $('#totalDoctorsCount').text(res.totalDoctors ?? $('#totalDoctorsCount').text());
                        var revenue = (res.revenueToday || 0);
                        $('#revenueToday').text('₹' + Number(revenue).toLocaleString());

                        // render lists (if present)
                        if (res.recentAppointments) renderRecentAppointments(res.recentAppointments);
                        if (res.activeDoctors) renderActiveDoctors(res.activeDoctors);
                        if (res.recentActivity) renderRecentActivity(res.recentActivity);
                    },
                    error: function() {
                        console.error('Could not load dashboard stats');
                    }
                });
            }

            $(document).ready(function() {
                loadStats();
                // refresh periodically
                setInterval(loadStats, 60000);
            });
        })();
    </script>
@endpush
