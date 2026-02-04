@extends('layouts.doctor')

@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')

    <div class="max-w-12xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Leave Management</h3>
                <p class="text-sm text-gray-500">View your leave history and apply for new leaves</p>
            </div>
            <button type="button" id="toggleFormBtn"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Apply for Leave
            </button>
        </div>

        <!-- Apply Leave Form (Hidden by default) -->
        <div id="leaveFormSection" class="hidden mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Request Leave</h4>
                    <button type="button" id="closeFormBtn" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form action="#" method="POST" id="leaveForm" class="space-y-6">
                    @csrf

                    <!-- Date Range with Visual Display -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- From Date -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">From <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center">
                                <input type="date" name="start_date" id="start_date"
                                    class="w-full bg-transparent border-0 p-0 text-lg font-semibold text-gray-800 focus:ring-0 focus:outline-none shadow-none cursor-pointer">
                            </div>
                            <div id="from_display" class="text-sm text-gray-500 mt-1">
                                <!-- Will be populated by JS -->
                            </div>
                            <div class="start_date_error_placeholder"></div>
                        </div>

                        <!-- Duration Display -->
                        <div
                            class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex flex-col items-center justify-center">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Duration</label>
                            <div id="duration_display" class="text-2xl font-bold text-sky-600">
                                0 days
                            </div>
                            <div class="text-sm text-gray-500 mt-1" id="leave_type_display">
                                <!-- Will show "Full day" or "Custom" -->
                            </div>
                        </div>

                        <!-- To Date -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">To <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center">
                                <input type="date" name="end_date" id="end_date"
                                    class="w-full bg-transparent border-0 p-0 text-lg font-semibold text-gray-800 focus:ring-0 focus:outline-none shadow-none cursor-pointer">
                            </div>
                            <div id="to_display" class="text-sm text-gray-500 mt-1">
                                <!-- Will be populated by JS -->
                            </div>
                            <div class="end_date_error_placeholder"></div>
                        </div>
                    </div>

                    <!-- Leave Type Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Select type of leave you want to
                            apply</label>

                        <!-- Leave Type Radio Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="leave_type" value="full_day" class="hidden peer" checked>
                                <div
                                    class="peer-checked:border-sky-600 peer-checked:bg-sky-50 peer-checked:text-sky-700 border border-gray-200 rounded-xl p-4 text-center transition">
                                    <p class="font-medium">Full days</p>
                                    <p class="text-xs text-gray-500">All selected days</p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="leave_type" value="custom" class="hidden peer">
                                <div
                                    class="peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:text-purple-700 border border-gray-200 rounded-xl p-4 text-center transition">
                                    <p class="font-medium">Custom</p>
                                    <p class="text-xs text-gray-500">Mix of full/half days</p>
                                </div>
                            </label>
                        </div>

                        <!-- Custom Days Selection (Hidden by default) -->
                        <!-- Custom Days Selection (Hidden by default) -->
                        <div id="customDaysSection" class="hidden mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select half days for start and end
                                dates</label>
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 flex flex-col gap-2">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                    <div class="flex flex-col items-start flex-1">
                                        <span class="text-xs text-gray-500 mb-1">From</span>
                                        <span class="font-medium text-gray-800 text-base" id="start_date_label">Start
                                            Date</span>
                                        <span class="text-xs text-gray-500" id="start_day_name"></span>
                                    </div>
                                    <select name="start_half_select" id="start_half_select"
                                        class="w-full sm:w-40 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                                        <option value="full_day">Full Day</option>
                                        <option value="first_half">First Half</option>
                                        <option value="second_half">Second Half</option>
                                    </select>
                                    <span class="mx-2 text-gray-400 text-lg font-bold">-</span>
                                    <div class="flex flex-col items-start flex-1">
                                        <span class="text-xs text-gray-500 mb-1">To</span>
                                        <span class="font-medium text-gray-800 text-base" id="end_date_label">End
                                            Date</span>
                                        <span class="text-xs text-gray-500" id="end_day_name"></span>
                                    </div>
                                    <select name="end_half_select" id="end_half_select"
                                        class="w-full sm:w-40 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                                        <option value="full_day">Full Day</option>
                                        <option value="first_half">First Half</option>
                                        <option value="second_half">Second Half</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Hidden fields for backend processing -->
                            <input type="hidden" name="start_date_type" id="start_date_type" value="full_day">
                            <input type="hidden" name="start_half_slot" id="start_half_slot" value="morning">
                            <input type="hidden" name="end_date_type" id="end_date_type" value="full_day">
                            <input type="hidden" name="end_half_slot" id="end_half_slot" value="morning">
                        </div>

                        <!-- Leave Days Summary -->
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mt-4">
                            <p class="text-sm text-blue-700" id="leave_summary">
                                You are requesting for <span class="font-bold">0 day</span> of leave
                            </p>
                        </div>
                    </div>

                    <!-- Adhoc Checkbox -->
                    <div class="mb-6">
                        <label
                            class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="is_adhoc" value="1" id="is_adhoc"
                                class="w-4 h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Adhoc Leave (Urgent/Immediate)</span>
                                <p class="text-xs text-gray-500 mt-1" id="adhoc_description">
                                    Check this if this is an urgent/adhoc leave request. Adhoc leaves are automatically
                                    approved and will cancel conflicting appointments.
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Reason -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Reason <span class="text-red-500">*</span>
                        </label>
                        <div class="border border-gray-300 rounded-xl overflow-hidden">
                            <textarea name="reason" rows="4"
                                class="w-full border-0 p-4 focus:ring-0 focus:outline-none shadow-none transition"
                                placeholder="Enter reason for leave..."></textarea>
                            <div class="reason_error_placeholder"></div>
                            <div class="bg-gray-50 px-4 py-2 text-xs text-gray-500">
                                Note: Please provide a valid reason for your leave request
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelFormBtn"
                            class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700 transition">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Appointment Conflict Alert -->
        <div id="appointmentConflictModal"
            class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 animate-fade-in">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                        <h2 class="ml-3 text-lg font-semibold text-gray-800">
                            Appointment Conflict
                        </h2>
                    </div>

                    <p id="appointmentConflictText" class="text-gray-700 mb-6">
                        You have appointments scheduled during this leave period.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button type="button" id="closeConflictModal"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700">
                            Cancel
                        </button>

                        <a href="{{ route('doctor.appointments') }}"
                            class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg">
                            View Appointments
                        </a>

                        <button type="button" id="proceedLeaveBtn"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Proceed Anyway
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 mb-6">

            <!-- Total Leaves -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-md">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">Total Leaves</p>
                        <p class="text-lg font-bold text-gray-800" id="totalLeaves">
                            {{ $leaves->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-md">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">Pending</p>
                        <p class="text-lg font-bold text-yellow-600" id="pendingLeaves">
                            {{ $leaves->where('status', 'pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-md">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">Approved</p>
                        <p class="text-lg font-bold text-green-600" id="approvedLeaves">
                            {{ $leaves->where('status', 'approved')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-md">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">Rejected</p>
                        <p class="text-lg font-bold text-orange-600" id="rejectedLeaves">
                            {{ $leaves->where('status', 'rejected')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Cancelled -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-md">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">Cancelled</p>
                        <p class="text-lg font-bold text-red-600" id="cancelledLeaves">
                            {{ $leaves->where('status', 'cancelled')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaves List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h4 class="text-lg font-semibold text-gray-800">Leave History</h4>
            </div>

            @if ($leaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    LEAVE TYPE</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    START DATE</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    END DATE</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    DURATION</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    AVAILABILITY</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    REASON</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    STATUS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    APPLIED ON</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($leaves as $index => $leave)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($leave->leave_type == 'full_day')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                                @if ($leave->is_adhoc)
                                                @endif
                                                Full Day
                                                @if ($leave->is_adhoc)
                                                    (Adhoc)
                                                @endif
                                            </span>
                                        @elseif($leave->leave_type == 'custom')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                @if ($leave->is_adhoc)
                                                @endif
                                                Custom
                                                @if ($leave->is_adhoc)
                                                    (Adhoc)
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M, Y') }}
                                        {{-- @if ($leave->leave_type == 'custom' && $leave->start_date_type == 'half_day')
                                            <span class="text-xs text-gray-500">({{ ucfirst($leave->start_half_slot) }}
                                                Half)</span>
                                        @endif --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}
                                        {{-- @if ($leave->leave_type == 'custom' && $leave->end_date_type == 'half_day')
                                                <span class="text-xs text-gray-500">({{ ucfirst($leave->end_half_slot) }}
                                                    Half)</span>
                                            @endif --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        @php
                                            $days =
                                                \Carbon\Carbon::parse($leave->start_date)->diffInDays(
                                                    \Carbon\Carbon::parse($leave->end_date),
                                                ) + 1;
                                            if ($leave->leave_type == 'custom') {
                                                $totalDays = $days;
                                                if ($leave->start_date_type == 'half_day') {
                                                    $totalDays -= 0.5;
                                                }
                                                if ($leave->end_date_type == 'half_day') {
                                                    $totalDays -= 0.5;
                                                }
                                                // If only one half day (start==end, both half_day), show 'Half Day'
                                                if (
                                                    $days == 1 &&
                                                    $leave->start_date_type == 'half_day' &&
                                                    $leave->end_date_type == 'half_day'
                                                ) {
                                                    echo 'Half Day';
                                                } elseif ($totalDays == 0.5) {
                                                    echo 'Half Day';
                                                } else {
                                                    echo rtrim(rtrim(number_format($totalDays, 1), '0'), '.') .
                                                        ' ' .
                                                        ($totalDays == 1 ? 'day' : 'days');
                                                }
                                            } else {
                                                echo $days . ' ' . ($days == 1 ? 'day' : 'days');
                                            }
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        @if ($leave->leave_type == 'custom')
                                            @php
                                                $isSingleDay = $leave->start_date == $leave->end_date;
                                            @endphp
                                            @if ($isSingleDay && $leave->start_date_type == 'half_day' && $leave->end_date_type == 'half_day')
                                                {{ ucfirst($leave->start_half_slot) }} Half
                                            @elseif ($isSingleDay && $leave->start_date_type == 'half_day')
                                                {{ ucfirst($leave->start_half_slot) }} Half
                                            @elseif ($isSingleDay && $leave->end_date_type == 'half_day')
                                                {{ ucfirst($leave->end_half_slot) }} Half
                                            @else
                                                @if ($leave->start_date_type == 'half_day')
                                                    {{ ucfirst($leave->start_half_slot) }} Half (Start)
                                                @endif
                                                @if ($leave->end_date_type == 'half_day')
                                                    {{ ucfirst($leave->end_half_slot) }} Half (End)
                                                @endif
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">
                                        {{ $leave->reason ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($leave->status == 'pending')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-400"></span>
                                                Pending
                                            </span>
                                        @elseif($leave->status == 'approved')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-400"></span>
                                                Approved
                                            </span>
                                        @elseif($leave->status == 'rejected')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-400"></span>
                                                Rejected
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $leave->created_at->format('d M, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="border-t border-gray-100 px-6 py-4 text-sky-600">
                        {{ $leaves->links('pagination::tailwind') }}
                    </div>

                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No leaves found</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't applied for any leave yet.</p>
                    <div class="mt-6">
                        <button type="button" id="applyFirstLeaveBtn"
                            class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Apply for Leave
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Helper: Move error messages under the correct divs
            function moveErrorMessages() {
                // For start_date
                let startDateError = $("#leaveForm input[name='start_date']").next('.error-text');
                if (startDateError.length) {
                    $('.start_date_error_placeholder').html(startDateError);
                } else {
                    $('.start_date_error_placeholder').empty();
                }
                // For end_date
                let endDateError = $("#leaveForm input[name='end_date']").next('.error-text');
                if (endDateError.length) {
                    $('.end_date_error_placeholder').html(endDateError);
                } else {
                    $('.end_date_error_placeholder').empty();
                }
                // For reason
                let reasonError = $("#leaveForm textarea[name='reason']").next('.error-text');
                if (reasonError.length) {
                    $('.reason_error_placeholder').html(reasonError);
                } else {
                    $('.reason_error_placeholder').empty();
                }
            }
            // Format date function
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            // Get day name
            function getDayName(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    weekday: 'long'
                });
            }

            // Calculate total duration
            function calculateTotalDuration() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const leaveType = $('input[name="leave_type"]:checked').val();

                if (!startDate || !endDate) {
                    return 0;
                }

                const start = new Date(startDate);
                const end = new Date(endDate);

                // Ensure end date is not before start date
                if (end < start) {
                    $('#end_date').val(startDate);
                    return 0;
                }

                // Calculate difference in days
                const timeDiff = end.getTime() - start.getTime();
                const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

                // Calculate based on leave type
                if (leaveType === 'custom') {
                    let calculatedDays = totalDays;
                    const startDateType = $('#start_date_type').val();
                    const endDateType = $('#end_date_type').val();

                    // Adjust for half days
                    if (startDateType === 'half_day') {
                        calculatedDays -= 0.5;
                    }
                    if (endDateType === 'half_day') {
                        calculatedDays -= 0.5;
                    }

                    // Ensure minimum 0
                    return Math.max(calculatedDays, 0);
                } else {
                    // Full days
                    return totalDays;
                }
            }

            // Update date displays
            function updateDateDisplays() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (startDate) {
                    $('#from_display').text(formatDate(startDate));
                    $('#start_date_label').text(formatDate(startDate).split(',')[0]);
                    $('#start_day_name').text(getDayName(startDate));
                } else {
                    $('#from_display').text('Select date');
                    $('#start_date_label').text('Start Date');
                    $('#start_day_name').text('');
                }

                if (endDate) {
                    $('#to_display').text(formatDate(endDate));
                    $('#end_date_label').text(formatDate(endDate).split(',')[0]);
                    $('#end_day_name').text(getDayName(endDate));
                } else {
                    $('#to_display').text('Select date');
                    $('#end_date_label').text('End Date');
                    $('#end_day_name').text('');
                }

                // Update duration and leave type display
                updateDurationAndSummary();
            }

            // Update duration and summary
            function updateDurationAndSummary() {
                const duration = calculateTotalDuration();
                const leaveType = $('input[name="leave_type"]:checked').val();

                if (duration > 0) {
                    // Format display (show .0 for whole numbers)
                    const displayDuration = duration % 1 === 0 ? duration.toString() : duration.toFixed(1);
                    $('#duration_display').text(`${displayDuration} ${duration === 1 ? 'day' : 'days'}`);

                    let typeText = leaveType === 'custom' ? 'Custom' : 'Full days';
                    $('#leave_type_display').text(typeText);

                    // Update leave summary
                    const summaryText =
                        `You are requesting for <span class="font-bold">${displayDuration} ${duration === 1 ? 'day' : 'days'}</span> of leave`;
                    $('#leave_summary').html(summaryText);
                } else {
                    $('#duration_display').text('0 days');
                    $('#leave_type_display').text('');
                    $('#leave_summary').html(
                        'You are requesting for <span class="font-bold">0 day</span> of leave');
                }
            }

            // Update custom section display
            // Update custom section display for the new select-based UI
            function updateCustomSectionDisplay() {
                const startDateType = $('#start_date_type').val();
                const startHalfSlot = $('#start_half_slot').val();
                const endDateType = $('#end_date_type').val();
                const endHalfSlot = $('#end_half_slot').val();

                // Update select values based on hidden fields
                if (startDateType === 'full_day') {
                    $('#start_half_select').val('full_day');
                } else {
                    $('#start_half_select').val(startHalfSlot === 'morning' ? 'first_half' : 'second_half');
                }

                if (endDateType === 'full_day') {
                    $('#end_half_select').val('full_day');
                } else {
                    $('#end_half_select').val(endHalfSlot === 'morning' ? 'first_half' : 'second_half');
                }
            }

            // Handle select changes for custom days
            $('#start_half_select').on('change', function() {
                const value = $(this).val();

                if (value === 'full_day') {
                    $('#start_date_type').val('full_day');
                    $('#start_half_slot').val('morning'); // default
                } else {
                    $('#start_date_type').val('half_day');
                    $('#start_half_slot').val(value === 'first_half' ? 'morning' : 'evening');
                }

                updateDurationAndSummary();
            });

            $('#end_half_select').on('change', function() {
                const value = $(this).val();

                if (value === 'full_day') {
                    $('#end_date_type').val('full_day');
                    $('#end_half_slot').val('morning'); // default
                } else {
                    $('#end_date_type').val('half_day');
                    $('#end_half_slot').val(value === 'first_half' ? 'morning' : 'evening');
                }

                updateDurationAndSummary();
            });
            // Handle adhoc checkbox changes
            function handleAdhocChange() {
                const isAdhoc = $('#is_adhoc').is(':checked');

                if (isAdhoc) {
                    // Hide Approval Type section when adhoc is checked
                    $('#approvalTypeSection').addClass('hidden');
                    // Auto set approval_type to 'auto' in backend
                    $('input[name="approval_type"]').val('auto');
                    // Update adhoc description
                    $('#adhoc_description').text(
                        'Adhoc leaves are automatically approved and will cancel conflicting appointments immediately.'
                    );
                } else {
                    // Show Approval Type section when adhoc is unchecked
                    $('#approvalTypeSection').removeClass('hidden');
                    // Reset to default selection
                    $('input[name="approval_type"][value="admin"]').prop('checked', true);
                    // Reset adhoc description
                    $('#adhoc_description').text(
                        'Check this if this is an urgent/adhoc leave request. Adhoc leaves are automatically approved and will cancel conflicting appointments.'
                    );
                }
            }

            // Set today's date as default for start date
            function setDefaultDates() {
                const today = new Date();
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);

                const formatDateForInput = (date) => {
                    return date.toISOString().split('T')[0];
                };

                $('#start_date').val(formatDateForInput(today));
                $('#end_date').val(formatDateForInput(tomorrow));

                updateDateDisplays();
                updateCustomSectionDisplay();
            }

            // Initialize form
            function initializeForm() {
                setDefaultDates();
                handleAdhocChange(); // Initialize adhoc state
            }

            // Event Listeners
            $('#start_date, #end_date').on('change', function() {
                updateDateDisplays();
            });

            $('input[name="leave_type"]').on('change', function() {
                const value = this.value;

                if (value === 'custom') {
                    $('#customDaysSection').removeClass('hidden');
                } else {
                    $('#customDaysSection').addClass('hidden');
                }

                updateDurationAndSummary();
            });

            // Start date option button click
            $(document).on('click', '.start-option-btn', function() {
                const type = $(this).data('type');
                const slot = $(this).data('slot') || 'morning';

                $('#start_date_type').val(type);
                if (type === 'half_day') {
                    $('#start_half_slot').val(slot);
                }

                updateCustomSectionDisplay();
                updateDurationAndSummary();
            });

            // End date option button click
            $(document).on('click', '.end-option-btn', function() {
                const type = $(this).data('type');
                const slot = $(this).data('slot') || 'morning';

                $('#end_date_type').val(type);
                if (type === 'half_day') {
                    $('#end_half_slot').val(slot);
                }

                updateCustomSectionDisplay();
                updateDurationAndSummary();
            });

            // Adhoc checkbox change
            $('#is_adhoc').on('change', function() {
                handleAdhocChange();
            });

            // Toggle form visibility
            function showForm() {
                $('#leaveFormSection').removeClass('hidden');
                $('#toggleFormBtn').html(`
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                `);
                initializeForm();
                $('html, body').animate({
                    scrollTop: $('#leaveFormSection').offset().top - 100
                }, 300);
            }

            function hideForm() {
                $('#leaveFormSection').addClass('hidden');
                $('#toggleFormBtn').html(`
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Apply for Leave
                `);
                $('#leaveForm')[0].reset();
                $('#customDaysSection').addClass('hidden');
                // Reset custom fields
                $('#start_date_type').val('full_day');
                $('#start_half_slot').val('morning');
                $('#end_date_type').val('full_day');
                $('#end_half_slot').val('morning');
                // Reset adhoc state
                $('#is_adhoc').prop('checked', false);
                updateCustomSectionDisplay();
                updateDurationAndSummary();
                handleAdhocChange(); // Reset approval type section
            }

            $('#toggleFormBtn').on('click', function() {
                if ($('#leaveFormSection').hasClass('hidden')) {
                    showForm();
                } else {
                    hideForm();
                }
            });

            $('#closeFormBtn, #cancelFormBtn').on('click', function() {
                hideForm();
            });

            $('#applyFirstLeaveBtn').on('click', function() {
                showForm();
            });

            // AJAX submit
            $('#leaveForm').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);

                // For adhoc leaves, ensure approval_type is 'auto'
                if ($('#is_adhoc').is(':checked')) {
                    $('input[name="approval_type"]').val('auto');
                }

                let formData = form.serialize();

                // Clear previous errors
                form.find('.error-text').remove();
                form.find('.border-red-500').removeClass('border-red-500');

                // Validate required fields
                let isValid = true;
                if (!$('#start_date').val()) {
                    $('#start_date').addClass('border-red-500');
                    $('#start_date').after(
                        '<p class="text-red-500 text-sm mt-1 error-text">Start date is required</p>');
                    isValid = false;
                }

                if (!$('#end_date').val()) {
                    $('#end_date').addClass('border-red-500');
                    $('#end_date').after(
                        '<p class="text-red-500 text-sm mt-1 error-text">End date is required</p>');
                    isValid = false;
                }

                if (!$('textarea[name="reason"]').val().trim()) {
                    $('textarea[name="reason"]').addClass('border-red-500');
                    $('textarea[name="reason"]').after(
                        '<p class="text-red-500 text-sm mt-1 error-text">Reason is required</p>');
                    isValid = false;
                }

                const leaveType = $('input[name="leave_type"]:checked').val();

                if (leaveType === 'custom') {
                    const startDateType = $('#start_date_type').val();
                    const endDateType = $('#end_date_type').val();

                    if (startDateType === 'half_day' && !$('#start_half_slot').val()) {
                        isValid = false;
                        $('.start-date-options').addClass('border-red-500');
                    }

                    if (endDateType === 'half_day' && !$('#end_half_slot').val()) {
                        isValid = false;
                        $('.end-date-options').addClass('border-red-500');
                    }
                }

                if (!isValid) {
                    return;
                }

                $.ajax({
                    url: "{{ route('doctor.leaves.store') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    beforeSend: function() {
                        $('#leave_submit_button').prop('disabled', true).text('Submitting...');
                    },
                    success: function(response) {
                        toastr.success(response.message ?? 'Leave request submitted');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;

                            Object.keys(errors).forEach(function(key) {
                                let field = form.find(`[name="${key}"]`);

                                // Highlight field
                                field.addClass('border-red-500');

                                // Insert error message after the field
                                if (field.next('.error-text').length === 0) {
                                    field.after(
                                        `<p class="text-red-500 text-sm mt-1 error-text">${errors[key][0]}</p>`
                                    );
                                }
                            });
                            return;
                        }

                        if (xhr.status === 409 && xhr.responseJSON.type ===
                            'appointment_conflict') {
                            let msg = xhr.responseJSON.message;
                            let appointments = xhr.responseJSON.appointments || [];
                            let detailsHtml = '';
                            if (appointments.length > 0) {
                                detailsHtml += '<div class="mb-3 text-sm text-gray-600">';
                                detailsHtml += '<b>Conflicting Appointments:</b>';
                                detailsHtml += '<ul class="list-disc pl-5">';
                                appointments.forEach(function(appt) {
                                    detailsHtml +=
                                        `<li><b>${appt.appointment_date}</b> - ${appt.patient_name} <span class='text-xs text-gray-500'>(${appt.status})</span></li>`;
                                });
                                detailsHtml += '</ul></div>';
                            }
                            $('#appointmentConflictText').html(msg + detailsHtml);
                            $('#appointmentConflictModal').removeClass('hidden');
                            return;
                        }

                        if (xhr.status === 422 && xhr.responseJSON.type === 'leave_conflict') {
                            toastr.error(xhr.responseJSON.message);
                            return;
                        }

                        toastr.error('Something went wrong. Please try again.');
                    },
                    complete: function() {
                        $('#leave_submit_button').prop('disabled', false).text(
                            'Submit Leave Request');
                    }
                });
            });

            // Remove error messages and red border when user modifies input/select/textarea
            $('#leaveForm').on('input change', 'input, select, textarea', function() {
                $(this).removeClass('border-red-500');
                $(this).next('.error-text').remove();
                $(this).parent().next('.error-text').remove();
                $('.start-date-options, .end-date-options').removeClass('border-red-500');
                moveErrorMessages();
            });

            // On AJAX error, move error messages if any
            $(document).ajaxComplete(function() {
                moveErrorMessages();
            });
        });

        $('#closeConflictModal').on('click', function() {
            $('#appointmentConflictModal').addClass('hidden');
        });

        // Proceed with leave and cancel appointments
        $('#proceedLeaveBtn').on('click', function() {
            let form = $('#leaveForm');
            let formData = form.serialize() + '&cancel_appointments=1';

            $.ajax({
                url: "{{ route('doctor.leaves.store') }}",
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                beforeSend: function() {
                    $('#proceedLeaveBtn').prop('disabled', true).text('Processing...');
                },
                success: function(response) {
                    $('#appointmentConflictModal').addClass('hidden');
                    toastr.success(response.message ??
                        'Leave request submitted and appointments cancelled');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        Object.values(errors).forEach(error => {
                            toastr.error(error[0]);
                        });
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                complete: function() {
                    $('#proceedLeaveBtn').prop('disabled', false).text('Proceed Anyway');
                }
            });
        });
    </script>
@endpush
