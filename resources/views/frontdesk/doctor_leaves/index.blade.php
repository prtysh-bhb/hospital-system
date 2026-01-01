@extends('layouts.frontdesk')


@section('title', 'Doctor Leaves')
@section('page-title', 'Doctor Leave Management')

@section('content')
    <div class="mx-auto px-4 py-6">

        {{-- Add Leave Card (Hidden by default) --}}
        <div id="leaveFormSection" class="hidden mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Apply Leave</h3>
                    <button type="button" id="hideFormBtn" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form id="leaveForm" class="space-y-4">
                    @csrf

                    <!-- Doctor Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            {{-- <label class="block text-sm font-medium mb-1">Doctor <span class="text-red-500">*</span></label> --}}
                            <select name="doctor_id"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Adhoc Checkbox -->
                        <div class="flex items-center h-10">
                            <label
                                class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="is_adhoc" value="1"
                                    class="w-4 h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
                                <span class="text-sm font-medium text-gray-700">Adhoc Leave (Urgent)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Date Range with Duration Display -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Start Date -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">From <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date"
                                class="w-full bg-transparent border-0 p-0 text-lg font-semibold text-gray-800 focus:ring-0 focus:outline-none cursor-pointer">
                            <div id="from_display" class="text-sm text-gray-500 mt-1"></div>
                        </div>

                        <!-- Duration Display -->
                        <div
                            class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col items-center justify-center">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Duration</label>
                            <div id="duration_display" class="text-2xl font-bold text-sky-600">0 days</div>
                            <div id="leave_type_display" class="text-sm text-gray-500 mt-1"></div>
                        </div>

                        <!-- End Date -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">To <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date"
                                class="w-full bg-transparent border-0 p-0 text-lg font-semibold text-gray-800 focus:ring-0 focus:outline-none cursor-pointer">
                            <div id="to_display" class="text-sm text-gray-500 mt-1"></div>
                        </div>
                    </div>

                    <!-- Leave Type Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select type of leave</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="leave_type" value="full_day" class="hidden peer" checked>
                                <div
                                    class="peer-checked:border-sky-600 peer-checked:bg-sky-50 peer-checked:text-sky-700 border border-gray-200 rounded-lg p-3 text-center transition">
                                    <p class="font-medium">Full days</p>
                                    <p class="text-xs text-gray-500">All selected days</p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="leave_type" value="custom" class="hidden peer">
                                <div
                                    class="peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:text-purple-700 border border-gray-200 rounded-lg p-3 text-center transition">
                                    <p class="font-medium">Custom</p>
                                    <p class="text-xs text-gray-500">Mix of full/half days</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Custom Days Selection (Hidden by default) -->
                    <div id="customDaysSection" class="hidden mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select half days for start and end
                            dates</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_half_select" class="block text-xs font-medium text-gray-600 mb-1">Start
                                    Date Type</label>
                                <select id="start_half_select" class="w-full border rounded-lg px-3 py-2 text-sm"
                                    name="start_half_select">
                                    <option value="full_day">Full Day</option>
                                    <option value="first_half">First Half</option>
                                    <option value="second_half">Second Half</option>
                                </select>
                            </div>
                            <div>
                                <label for="end_half_select" class="block text-xs font-medium text-gray-600 mb-1">End Date
                                    Type</label>
                                <select id="end_half_select" class="w-full border rounded-lg px-3 py-2 text-sm"
                                    name="end_half_select">
                                    <option value="full_day">Full Day</option>
                                    <option value="first_half">First Half</option>
                                    <option value="second_half">Second Half</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="start_date_type" id="start_date_type" value="full_day">
                        <input type="hidden" name="start_half_slot" id="start_half_slot" value="morning">
                        <input type="hidden" name="end_date_type" id="end_date_type" value="full_day">
                        <input type="hidden" name="end_half_slot" id="end_half_slot" value="morning">
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                        <p class="text-sm text-blue-700" id="leave_summary">
                            You are applying for <span class="font-bold">0 day</span> of leave
                        </p>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Reason <span class="text-red-500">*</span></label>
                        <textarea name="reason" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                            placeholder="Enter reason for leave..."></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelBtn"
                            class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-2 bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700 transition">
                            Apply Leave
                        </button>
                    </div>
                </form>

                <p id="formError" class="text-red-600 text-sm mt-3 hidden"></p>
            </div>
        </div>

        {{-- Leave List --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Current & Upcoming Leaves</h3><small
                        class="text-sm text-gray-500"> Total: {{ $leaves->total() }} leaves</small>
                </div>
                <div>
                    <button type="button" id="showFormBtn"
                        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Apply Leave
                    </button>
                </div>
            </div>

            @if ($leaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Doctor</th>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    From</th>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    To</th>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Availability</th>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reason</th>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leaveTableBody">
                            @foreach ($leaves as $leave)
                                <tr data-leave-id="{{ $leave->id }}" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border">
                                        <div class="font-medium text-gray-900">
                                            Dr. {{ $leave->doctor->first_name }} {{ $leave->doctor->last_name }}
                                        </div>
                                        @if ($leave->is_adhoc)
                                            <span class="text-xs text-amber-600 font-medium">(Adhoc)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="text-gray-900">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d M, Y') }}
                                        </div>
                                        @if ($leave->leave_type == 'custom' && $leave->start_date_type == 'half_day')
                                            <div class="text-xs text-gray-500">
                                                {{ ucfirst($leave->start_half_slot) }} Half
                                                @if ($leave->start_half_slot == 'morning')
                                                    <span class="text-gray-400">(9 AM - 1 PM)</span>
                                                @else
                                                    <span class="text-gray-400">(2 PM - 6 PM)</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="text-gray-900">
                                            {{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}
                                        </div>
                                        @if ($leave->leave_type == 'custom' && $leave->end_date_type == 'half_day')
                                            <div class="text-xs text-gray-500">
                                                {{ ucfirst($leave->end_half_slot) }} Half
                                                @if ($leave->end_half_slot == 'morning')
                                                    <span class="text-gray-400">(9 AM - 1 PM)</span>
                                                @else
                                                    <span class="text-gray-400">(2 PM - 6 PM)</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border">
                                        @if ($leave->leave_type == 'full_day')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                                Full Day
                                            </span>
                                        @elseif($leave->leave_type == 'custom')
                                            <div class="flex flex-col gap-1">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Custom
                                                </span>
                                                @php
                                                    $start = \Carbon\Carbon::parse($leave->start_date);
                                                    $end = \Carbon\Carbon::parse($leave->end_date);
                                                    $days = $start->diffInDays($end) + 1;

                                                    if ($leave->start_date_type == 'half_day') {
                                                        $days -= 0.5;
                                                    }
                                                    if ($leave->end_date_type == 'half_day') {
                                                        $days -= 0.5;
                                                    }
                                                    $displayDays = $days % 1 === 0 ? $days : number_format($days, 1);
                                                @endphp
                                                <span class="text-xs text-gray-500">{{ $displayDays }} day(s)</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border">
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
                                    <td class="px-4 py-3 border text-gray-700 max-w-xs truncate">{{ $leave->reason }}</td>
                                    <td class="px-4 py-3 border">
                                        @if ($leave->status == 'approved')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-400"></span>
                                                Approved
                                            </span>
                                            @if ($leave->approval_type == 'frontdesk')
                                                <div class="text-xs text-gray-500 mt-1">By Frontdesk</div>
                                            @endif
                                        @elseif($leave->status == 'pending')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-400"></span>
                                                Pending
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
                                    <td class="px-4 py-3 border">
                                        @if ($leave->status === 'pending')
                                            <div class="flex gap-2">
                                                <button
                                                    class="approve-btn bg-green-500 text-white px-3 py-1.5 rounded text-sm font-medium hover:bg-green-600 transition flex items-center gap-1"
                                                    data-id="{{ $leave->id }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Approve
                                                </button>
                                                <button
                                                    class="reject-btn bg-red-500 text-white px-3 py-1.5 rounded text-sm font-medium hover:bg-red-600 transition flex items-center gap-1"
                                                    data-id="{{ $leave->id }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Reject
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $leaves->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No leaves found</h3>
                    <p class="mt-1 text-sm text-gray-500">No doctor leaves have been applied yet.</p>
                    <div class="mt-6">
                        <button type="button" id="showFirstFormBtn"
                            class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Apply First Leave
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Calculate total duration
            function calculateTotalDuration() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const leaveType = document.querySelector('input[name="leave_type"]:checked').value;

                if (!startDate || !endDate) {
                    return 0;
                }

                const start = new Date(startDate);
                const end = new Date(endDate);

                if (end < start) {
                    document.getElementById('end_date').value = startDate;
                    return 0;
                }

                const timeDiff = end.getTime() - start.getTime();
                const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

                if (leaveType === 'custom') {
                    let calculatedDays = totalDays;

                    const startHalfVal = document.getElementById('start_half_select').value;
                    const endHalfVal = document.getElementById('end_half_select').value;

                    if (startHalfVal !== 'full_day') {
                        calculatedDays -= 0.5;
                    }
                    if (endHalfVal !== 'full_day') {
                        calculatedDays -= 0.5;
                    }

                    // If start and end are same day and both are half days of different types
                    if (totalDays === 1 && startHalfVal !== 'full_day' && endHalfVal !== 'full_day') {
                        if (startHalfVal !== endHalfVal) {
                            calculatedDays = 1; // Full day
                        } else {
                            calculatedDays = 0.5; // Same half day
                        }
                    }

                    return Math.max(calculatedDays, 0);
                } else {
                    return totalDays;
                }
            }

            // Update date displays
            function updateDateDisplays() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if (startDate) {
                    document.getElementById('from_display').textContent = formatDate(startDate);
                } else {
                    document.getElementById('from_display').textContent = 'Select date';
                }

                if (endDate) {
                    document.getElementById('to_display').textContent = formatDate(endDate);
                } else {
                    document.getElementById('to_display').textContent = 'Select date';
                }

                updateDurationAndSummary();
            }

            // Update duration and summary
            function updateDurationAndSummary() {
                const duration = calculateTotalDuration();
                const leaveType = document.querySelector('input[name="leave_type"]:checked').value;

                if (duration > 0) {
                    const displayDuration = duration % 1 === 0 ? duration.toString() : duration.toFixed(1);
                    document.getElementById('duration_display').textContent =
                        `${displayDuration} ${duration === 1 ? 'day' : 'days'}`;

                    let typeText = leaveType === 'custom' ? 'Custom' : 'Full days';
                    document.getElementById('leave_type_display').textContent = typeText;

                    const summaryText =
                        `You are applying for <span class="font-bold">${displayDuration} ${duration === 1 ? 'day' : 'days'}</span> of leave`;
                    document.getElementById('leave_summary').innerHTML = summaryText;
                } else {
                    document.getElementById('duration_display').textContent = '0 days';
                    document.getElementById('leave_type_display').textContent = '';
                    document.getElementById('leave_summary').innerHTML =
                        'You are applying for <span class="font-bold">0 day</span> of leave';
                }
            }

            // Handle select changes for custom days
            document.getElementById('start_half_select')?.addEventListener('change', function() {
                const value = this.value;

                if (value === 'full_day') {
                    document.getElementById('start_date_type').value = 'full_day';
                    document.getElementById('start_half_slot').value = 'morning';
                } else {
                    document.getElementById('start_date_type').value = 'half_day';
                    document.getElementById('start_half_slot').value = value === 'first_half' ? 'morning' :
                        'evening';
                }

                updateDurationAndSummary();
            });

            document.getElementById('end_half_select')?.addEventListener('change', function() {
                const value = this.value;

                if (value === 'full_day') {
                    document.getElementById('end_date_type').value = 'full_day';
                    document.getElementById('end_half_slot').value = 'morning';
                } else {
                    document.getElementById('end_date_type').value = 'half_day';
                    document.getElementById('end_half_slot').value = value === 'first_half' ? 'morning' :
                        'evening';
                }

                updateDurationAndSummary();
            });

            // Set default dates
            function setDefaultDates() {
                const today = new Date();
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);

                const formatDateForInput = (date) => {
                    return date.toISOString().split('T')[0];
                };

                document.getElementById('start_date').value = formatDateForInput(today);
                document.getElementById('end_date').value = formatDateForInput(tomorrow);

                updateDateDisplays();
            }

            // Initialize form
            function initializeForm() {
                setDefaultDates();
            }

            // Show/Hide form
            function showForm() {
                document.getElementById('leaveFormSection').classList.remove('hidden');
                initializeForm();
                setTimeout(() => {
                    document.getElementById('leaveFormSection').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }

            function hideForm() {
                document.getElementById('leaveFormSection').classList.add('hidden');
                document.getElementById('leaveForm').reset();
                document.getElementById('customDaysSection').classList.add('hidden');
                document.getElementById('start_date_type').value = 'full_day';
                document.getElementById('start_half_slot').value = 'morning';
                document.getElementById('end_date_type').value = 'full_day';
                document.getElementById('end_half_slot').value = 'morning';
                document.getElementById('formError').classList.add('hidden');
                updateDurationAndSummary();
            }

            // Event Listeners
            document.getElementById('showFormBtn').addEventListener('click', function(e) {
                e.preventDefault();
                if (document.getElementById('leaveFormSection').classList.contains('hidden')) {
                    showForm();
                } else {
                    hideForm();
                }
            });

            document.getElementById('showFirstFormBtn')?.addEventListener('click', function(e) {
                e.preventDefault();
                showForm();
            });

            document.getElementById('hideFormBtn').addEventListener('click', function(e) {
                e.preventDefault();
                hideForm();
            });

            document.getElementById('cancelBtn').addEventListener('click', function(e) {
                e.preventDefault();
                hideForm();
            });

            // Date change events
            document.getElementById('start_date').addEventListener('change', updateDateDisplays);
            document.getElementById('end_date').addEventListener('change', updateDateDisplays);

            // Leave type change
            document.querySelectorAll('input[name="leave_type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const value = this.value;
                    if (value === 'custom') {
                        document.getElementById('customDaysSection').classList.remove('hidden');
                    } else {
                        document.getElementById('customDaysSection').classList.add('hidden');
                    }
                    updateDurationAndSummary();
                });
            });

            // Form submission
            document.getElementById('leaveForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const errorBox = document.getElementById('formError');
                errorBox.classList.add('hidden');

                // Validation
                let isValid = true;
                const doctorSelect = document.querySelector('select[name="doctor_id"]');
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const reasonTextarea = document.querySelector('textarea[name="reason"]');

                if (!doctorSelect.value) {
                    doctorSelect.classList.add('border-red-500');
                    isValid = false;
                }
                if (!startDateInput.value) {
                    startDateInput.closest('.bg-gray-50').classList.add('border-red-500');
                    isValid = false;
                }
                if (!endDateInput.value) {
                    endDateInput.closest('.bg-gray-50').classList.add('border-red-500');
                    isValid = false;
                }
                if (!reasonTextarea.value.trim()) {
                    reasonTextarea.classList.add('border-red-500');
                    isValid = false;
                }

                if (!isValid) {
                    errorBox.textContent = 'Please fill all required fields.';
                    errorBox.classList.remove('hidden');
                    return;
                }

                const formData = new FormData(this);

                function submitLeave(force = false) {
                    if (force) formData.set('force', '1');

                    const jsonData = {};
                    formData.forEach((value, key) => {
                        jsonData[key] = value;
                    });

                    fetch("{{ route('frontdesk.doctor-leaves.store') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('input[name=_token]').value,
                                "Accept": "application/json",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(jsonData)
                        })
                        .then(async res => {
                            const data = await res.json();
                            if (res.status === 409 && data.type === 'appointment_conflict') {
                                const appts = data.appointments.map(a =>
                                    `#${a.id}: ${a.appointment_date} (${a.status})`
                                ).join('\n');
                                if (confirm(
                                        `There are appointments during this leave period:\n\n${appts}\n\nDo you want to proceed and cancel these appointments?`
                                    )) {
                                    submitLeave(true);
                                }
                                return;
                            }
                            if (res.status === 422 && data.type === 'leave_conflict') {
                                errorBox.textContent = data.message ||
                                    'Doctor already has a leave for the selected dates.';
                                errorBox.classList.remove('hidden');
                                return;
                            }
                            if (!data.success) {
                                errorBox.textContent = data.message ||
                                    "Something went wrong. Please check inputs.";
                                errorBox.classList.remove('hidden');
                                return;
                            }

                            // Add row to table
                            const row = `
                    <tr data-leave-id="${data.data.id}" class="hover:bg-gray-50">
                        <td class="px-4 py-3 border">
                            <div class="font-medium text-gray-900">${data.data.doctor}</div>
                            ${data.data.is_adhoc ? '<span class="text-xs text-amber-600 font-medium">(Adhoc)</span>' : ''}
                        </td>
                        <td class="px-4 py-3 border">
                            <div class="text-gray-900">${data.data.start_date_formatted}</div>
                            ${data.data.start_half_slot ? `<div class="text-xs text-gray-500">${data.data.start_half_slot} Half</div>` : ''}
                        </td>
                        <td class="px-4 py-3 border">
                            <div class="text-gray-900">${data.data.end_date_formatted}</div>
                            ${data.data.end_half_slot ? `<div class="text-xs text-gray-500">${data.data.end_half_slot} Half</div>` : ''}
                        </td>
                        <td class="px-4 py-3 border">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${data.data.leave_type === 'full_day' ? 'bg-sky-100 text-sky-800' : 'bg-purple-100 text-purple-800'}">
                                    ${data.data.leave_type_display}
                                </span>
                                ${data.data.leave_type === 'custom' && data.data.records_created > 1 ? `<span class="text-xs text-gray-500">${data.data.records_created} records</span>` : ''}
                            </div>
                        </td>
                        <td class="px-4 py-3 border">
                            ${data.data.availability ? data.data.availability : '-'}
                        </td>
                        <td class="px-4 py-3 border text-gray-700 max-w-xs truncate">${data.data.reason}</td>
                        <td class="px-4 py-3 border">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-400"></span>
                                Approved
                            </span>
                            <div class="text-xs text-gray-500 mt-1">By Frontdesk</div>
                        </td>
                        <td class="px-4 py-3 border">
                            <span class="text-gray-400 text-sm">-</span>
                        </td>
                    </tr>
                `;

                            const tableBody = document.getElementById('leaveTableBody');
                            if (!tableBody || tableBody.innerHTML.includes('No leaves found')) {
                                // If table doesn't exist or shows "no leaves", replace the whole section
                                const newTable = `
                            <div class="overflow-x-auto">
                                <table class="min-w-full border text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                            <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                            <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                                            <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                            <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="leaveTableBody">
                                        ${row}
                                    </tbody>
                                </table>
                            </div>
                        `;
                                document.querySelector(
                                        '.bg-white.rounded-xl.shadow.p-6 > div:last-child')
                                    .innerHTML = newTable;
                            } else {
                                tableBody.insertAdjacentHTML('afterbegin', row);
                            }

                            hideForm();
                            if (typeof toastr !== 'undefined') {
                                toastr.success(data.message || 'Leave added successfully');
                            } else {
                                alert(data.message || 'Leave added successfully');
                            }
                        })
                        .catch(() => {
                            errorBox.textContent = "Something went wrong. Please check inputs.";
                            errorBox.classList.remove('hidden');
                        });
                }
                submitLeave(false);
            });

            // Approve/Reject buttons (delegated event handling)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.approve-btn')) {
                    e.preventDefault();
                    const button = e.target.closest('.approve-btn');
                    const id = button.getAttribute('data-id');

                    // Show custom modal for approval confirmation
                    showApprovalModal({
                        onConfirm: function() {
                            button.disabled = true;
                            button.innerHTML =
                                '<span class="flex items-center gap-1"><svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Approving...</span>';
                            fetch(`/frontdesk/doctor-leaves/${id}/approve`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'input[name=_token]').value,
                                    'Accept': 'application/json'
                                }
                            }).then(async res => {
                                const data = await res.json();
                                if (res.status === 409 && data.type ===
                                    'appointment_conflict') {
                                    showConflictModal(data.message, function() {
                                        // Proceed anyway
                                        fetch(`/frontdesk/doctor-leaves/${id}/approve?force=1`, {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': document
                                                        .querySelector(
                                                            'input[name=_token]'
                                                        ).value,
                                                    'Accept': 'application/json'
                                                }
                                            }).then(res2 => res2.json())
                                            .then(res2 => {
                                                if (res2.success) {
                                                    updateRowApproved(
                                                        id, res2
                                                        .message);
                                                } else {
                                                    button.disabled =
                                                        false;
                                                    button.innerHTML =
                                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Approve';
                                                    if (typeof toastr !==
                                                        'undefined')
                                                        toastr.error(
                                                            res2
                                                            .message ||
                                                            'Failed to approve leave'
                                                        );
                                                }
                                            });
                                    });
                                } else if (data.success) {
                                    updateRowApproved(id, data.message);
                                } else {
                                    button.disabled = false;
                                    button.innerHTML =
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Approve';
                                    if (typeof toastr !== 'undefined') toastr.error(
                                        data.message ||
                                        'Failed to approve leave');
                                }
                            }).catch(() => {
                                button.disabled = false;
                                button.innerHTML =
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Approve';
                                if (typeof toastr !== 'undefined') toastr.error(
                                    'Failed to approve leave');
                            });
                        }
                    });
                    // Modal for approval confirmation
                    function showApprovalModal({
                        onConfirm
                    }) {
                        let modal = document.getElementById('approvalModal');
                        if (!modal) {
                            modal = document.createElement('div');
                            modal.id = 'approvalModal';
                            modal.innerHTML = `
                        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 animate-fade-in">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Approve Leave</h2>
                                    <p class="mb-6 text-gray-700">Are you sure you want to approve this leave? Any conflicting appointments will be cancelled.</p>
                                    <div class="flex justify-end gap-3">
                                        <button id="cancelApprovalBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700">Cancel</button>
                                        <button id="confirmApprovalBtn" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                            document.body.appendChild(modal);
                        }
                        modal.style.display = 'flex';
                        document.getElementById('cancelApprovalBtn').onclick = function() {
                            modal.style.display = 'none';
                        };
                        document.getElementById('confirmApprovalBtn').onclick = function() {
                            modal.style.display = 'none';
                            if (onConfirm) onConfirm();
                        };
                    }

                    // Modal for appointment conflict
                    function showConflictModal(message, onProceed) {
                        let modal = document.getElementById('conflictModal');
                        if (!modal) {
                            modal = document.createElement('div');
                            modal.id = 'conflictModal';
                            modal.innerHTML = `
                        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 animate-fade-in">
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Appointment Conflict</h2>
                                    <p class="mb-6 text-gray-700">${message}</p>
                                    <div class="flex justify-end gap-3">
                                        <button id="cancelConflictBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700">Cancel</button>
                                        <button id="proceedConflictBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Proceed Anyway</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                            document.body.appendChild(modal);
                        } else {
                            modal.querySelector('p').textContent = message;
                        }
                        modal.style.display = 'flex';
                        document.getElementById('cancelConflictBtn').onclick = function() {
                            modal.style.display = 'none';
                        };
                        document.getElementById('proceedConflictBtn').onclick = function() {
                            modal.style.display = 'none';
                            if (onProceed) onProceed();
                        };
                    }

                    // Helper to update row after approval
                    function updateRowApproved(id, message) {
                        const row = document.querySelector(`tr[data-leave-id='${id}']`);
                        row.querySelector('td:nth-child(7)').innerHTML = `
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-400"></span>
                        Approved
                    </span>
                    <div class="text-xs text-gray-500 mt-1">By Frontdesk</div>
                `;
                        row.querySelector('td:nth-child(8)').innerHTML =
                            '<span class="text-gray-400 text-sm">-</span>';
                        if (typeof toastr !== 'undefined') {
                            toastr.success(message || 'Leave approved successfully');
                        }
                    }
                }

                if (e.target.closest('.reject-btn')) {
                    e.preventDefault();
                    const button = e.target.closest('.reject-btn');
                    const id = button.getAttribute('data-id');

                    if (!confirm('Are you sure you want to reject this leave?')) {
                        return;
                    }

                    button.disabled = true;
                    button.innerHTML =
                        '<span class="flex items-center gap-1"><svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Rejecting...</span>';

                    fetch(`/frontdesk/doctor-leaves/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                            'Accept': 'application/json'
                        }
                    }).then(res => res.json()).then(res => {
                        if (res.success) {
                            const row = document.querySelector(`tr[data-leave-id='${id}']`);
                            row.querySelector('td:nth-child(6)').innerHTML = `
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-400"></span>
                            Rejected
                        </span>
                    `;
                            row.querySelector('td:nth-child(7)').innerHTML =
                                '<span class="text-gray-400 text-sm">-</span>';
                            if (typeof toastr !== 'undefined') {
                                toastr.success(res.message || 'Leave rejected successfully');
                            }
                        } else {
                            button.disabled = false;
                            button.innerHTML =
                                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Reject';
                            if (typeof toastr !== 'undefined') {
                                toastr.error(res.message || 'Failed to reject leave');
                            }
                        }
                    }).catch(() => {
                        button.disabled = false;
                        button.innerHTML =
                            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Reject';
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Failed to reject leave');
                        }
                    });
                }
            });
        });
    </script>
@endsection
