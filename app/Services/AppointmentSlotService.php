<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorLeave;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class AppointmentSlotService
{
    /**
     * Get available time slots for a doctor on a specific date
     *
     * @param  int  $doctorId
     * @param  string  $date
     * @param  int|null  $excludeAppointmentId  - exclude this appointment when checking (for updates)
     * @return array
     */
    public function getAvailableSlots($doctorId, $date, $excludeAppointmentId = null)
    {
        if (! $doctorId || ! $date) {
            return [
                'success' => false,
                'message' => 'Doctor and date are required',
                'slots' => [],
            ];
        }

        // Ensure $date is Carbon instance
        $selectedDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        $now = Carbon::now();
        $isToday = $selectedDate->isToday();
        $weekday = $selectedDate->dayOfWeek;

        // Check if doctor is on leave for this date
        $leaveCheck = $this->isDoctorOnLeave($doctorId, $selectedDate->format('Y-m-d'));
        if ($leaveCheck['on_leave'] && $leaveCheck['leave_type'] === 'full_day') {
            return [
                'success' => false,
                'message' => $leaveCheck['message'],
                'slots' => [],
                'on_leave' => true,
                'leave_end_date' => $leaveCheck['leave_end_date'],
            ];
        }

        // Get doctor schedule
        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $weekday)
            ->where('is_available', true)
            ->first();

        if (! $schedule || ! $schedule->start_time || ! $schedule->end_time) {
            return [
                'success' => false,
                'message' => 'Doctor schedule not available for this date.',
                'slots' => [],
                'on_leave' => false,
            ];
        }

        $slots = [];
        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
        $slotDuration = $schedule->slot_duration ?? 30;

        // Get booked appointments for this doctor on this date
        $bookedQuery = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $selectedDate->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed', 'checked_in', 'in_progress']);

        if ($excludeAppointmentId) {
            $bookedQuery->where('id', '!=', $excludeAppointmentId);
        }

        $bookedSlots = $bookedQuery->pluck('appointment_time')
            ->map(function ($time) {
                try {
                    return Carbon::parse($time)->format('H:i');
                } catch (\Exception $e) {
                    return null;
                }
            })
            ->filter()
            ->toArray();

        while ($start < $end) {
            $timeSlot24 = $start->format('H:i');
            $timeSlot12 = $start->format('h:i A');

            // Skip booked slots
            if (in_array($timeSlot24, $bookedSlots)) {
                $start->addMinutes($slotDuration);

                continue;
            }

            // Check if slot is affected by half-day leave
            if ($leaveCheck['on_leave']) {
                $isBlocked = false;

                // For full-day leave (should have been caught above, but double-check)
                if ($leaveCheck['leave_type'] === 'full_day') {
                    $isBlocked = true;
                }
                // For half-day or custom leave types
                elseif ($leaveCheck['leave_type'] === 'half_day' || $leaveCheck['leave_type'] === 'custom') {
                    // Check if this specific date is affected by half-day leave
                    $dateCheck = $this->isDateAffectedByHalfDay($selectedDate, $leaveCheck);
                    if ($dateCheck['is_affected']) {
                        $isBlocked = $this->isSlotDuringHalfDayLeave($start, $dateCheck['half_slot']);
                    }
                }

                if ($isBlocked) {
                    $start->addMinutes($slotDuration);

                    continue;
                }
            }

            // For today, skip slots within 30 minutes
            if ($isToday) {
                $slotDateTime = Carbon::parse($selectedDate->format('Y-m-d').' '.$timeSlot24);
                if ($slotDateTime->gt($now->copy()->addMinutes(30))) {
                    $slots[] = $timeSlot12;
                }
            } else {
                $slots[] = $timeSlot12;
            }

            $start->addMinutes($slotDuration);
        }

        return [
            'success' => true,
            'slots' => $slots,
            'message' => count($slots) > 0 ? 'Slots available' : 'No slots available',
            'on_leave' => false,
        ];
    }

    /**
     * Check if doctor is on leave for a specific date
     *
     * @param  int  $doctorId
     * @param  string  $date
     * @return array
     */
    public function isDoctorOnLeave($doctorId, $date)
    {
        $leaves = DoctorLeave::where('doctor_id', $doctorId)
            ->where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->get();

        if ($leaves->isEmpty()) {
            return [
                'on_leave' => false,
                'message' => null,
                'leave_end_date' => null,
                'leave_type' => null,
                'leave_records' => [],
            ];
        }

        // Check if any leave record covers this date as full day
        foreach ($leaves as $leave) {
            $leaveType = $leave->leave_type;

            // For custom leaves, check the specific date's type
            if ($leaveType === 'custom') {
                // Check if this date is the start date
                $startDateStr = Carbon::parse($leave->start_date)->format('Y-m-d');
                $endDateStr = Carbon::parse($leave->end_date)->format('Y-m-d');
                if ($date == $startDateStr) {
                    if ($leave->start_date_type === 'full_day') {
                        // This date is full day leave
                        $endDate = Carbon::parse($leave->end_date)->format('d M, Y');

                        return [
                            'on_leave' => true,
                            'message' => "Doctor is on leave until {$endDate}. Please select another date or doctor.",
                            'leave_end_date' => $leave->end_date,
                            'leave_type' => 'full_day',
                            'leave_records' => $leaves,
                        ];
                    } elseif ($leave->start_date_type === 'half_day') {
                        // This date is half day leave (start date)
                        $endDate = Carbon::parse($leave->end_date)->format('d M, Y');

                        return [
                            'on_leave' => true,
                            'message' => 'Doctor is on half-day leave on this date. Limited slots available.',
                            'leave_end_date' => $leave->end_date,
                            'leave_type' => 'custom',
                            'leave_records' => $leaves,
                            'start_date_type' => 'half_day',
                            'start_half_slot' => $leave->start_half_slot,
                            'end_date_type' => $leave->end_date_type,
                            'end_half_slot' => $leave->end_half_slot,
                        ];
                    }
                }
                // Check if this date is the end date
                elseif ($date == $endDateStr) {
                    if ($leave->end_date_type === 'full_day') {
                        // This date is full day leave
                        $endDate = Carbon::parse($leave->end_date)->format('d M, Y');

                        return [
                            'on_leave' => true,
                            'message' => "Doctor is on leave until {$endDate}. Please select another date or doctor.",
                            'leave_end_date' => $leave->end_date,
                            'leave_type' => 'full_day',
                            'leave_records' => $leaves,
                        ];
                    } elseif ($leave->end_date_type === 'half_day') {
                        // This date is half day leave (end date)
                        $endDate = Carbon::parse($leave->end_date)->format('d M, Y');

                        return [
                            'on_leave' => true,
                            'message' => 'Doctor is on half-day leave on this date. Limited slots available.',
                            'leave_end_date' => $leave->end_date,
                            'leave_type' => 'custom',
                            'leave_records' => $leaves,
                            'start_date_type' => $leave->start_date_type,
                            'start_half_slot' => $leave->start_half_slot,
                            'end_date_type' => 'half_day',
                            'end_half_slot' => $leave->end_half_slot,
                        ];
                    }
                }
                // Date is between start and end (middle dates) - check if there's a separate middle record
                else {
                    // Look for a leave record that specifically covers this date as full day
                    foreach ($leaves as $middleLeave) {
                        if ($middleLeave->leave_type === 'custom' &&
                            $middleLeave->start_date_type === 'full_day' &&
                            $middleLeave->end_date_type === 'full_day' &&
                            $date >= $middleLeave->start_date->format('Y-m-d') &&
                            $date <= $middleLeave->end_date->format('Y-m-d')) {
                            // This date is covered by a middle full-day leave record
                            $endDate = Carbon::parse($middleLeave->end_date)->format('d M, Y');

                            return [
                                'on_leave' => true,
                                'message' => "Doctor is on leave until {$endDate}. Please select another date or doctor.",
                                'leave_end_date' => $middleLeave->end_date,
                                'leave_type' => 'full_day',
                                'leave_records' => $leaves,
                            ];
                        }
                    }
                }
            }
            // For full_day leaves
            elseif ($leaveType === 'full_day') {
                $endDate = Carbon::parse($leave->end_date)->format('d M, Y');

                return [
                    'on_leave' => true,
                    'message' => "Doctor is on leave until {$endDate}. Please select another date or doctor.",
                    'leave_end_date' => $leave->end_date,
                    'leave_type' => 'full_day',
                    'leave_records' => $leaves,
                ];
            }
            // For old half_day leaves (legacy)
            elseif ($leaveType === 'half_day') {
                $endDate = Carbon::parse($leave->end_date)->format('d M, Y');

                return [
                    'on_leave' => true,
                    'message' => 'Doctor is on half-day leave on this date. Limited slots available.',
                    'leave_end_date' => $leave->end_date,
                    'leave_type' => 'half_day',
                    'leave_records' => $leaves,
                    'half_day_slot' => $leave->half_day_slot,
                ];
            }
        }

        // If we get here, the date might be in the leave range but not specifically covered
        // (e.g., a middle date in a custom leave where start and end are half days)
        // Check if it's explicitly not covered by any half-day leave
        $isExplicitlyNotCovered = true;
        foreach ($leaves as $leave) {
            if ($leave->leave_type === 'custom') {
                // If there's a middle record covering this date as full day, it would have been caught above
                // So this date is available
                continue;
            }
        }

        if ($isExplicitlyNotCovered) {
            return [
                'on_leave' => false,
                'message' => null,
                'leave_end_date' => null,
                'leave_type' => null,
                'leave_records' => [],
            ];
        }

        // Default: assume not on leave
        return [
            'on_leave' => false,
            'message' => null,
            'leave_end_date' => null,
            'leave_type' => null,
            'leave_records' => [],
        ];
    }

    /**
     * Check if a specific date is affected by half-day leave
     *
     * @param  Carbon  $date
     * @param  array  $leaveCheck
     * @return array
     */
    private function isDateAffectedByHalfDay($date, $leaveCheck)
    {
        $dateStr = $date->format('Y-m-d');

        foreach ($leaveCheck['leave_records'] as $leave) {
            if ($dateStr >= $leave->start_date->format('Y-m-d') &&
                $dateStr <= $leave->end_date->format('Y-m-d')) {

                // For custom leaves
                if ($leave->leave_type === 'custom') {
                    if ($dateStr == $leave->start_date->format('Y-m-d') &&
                        $leave->start_date_type === 'half_day') {
                        return [
                            'is_affected' => true,
                            'half_slot' => $leave->start_half_slot,
                        ];
                    }

                    if ($dateStr == $leave->end_date->format('Y-m-d') &&
                        $leave->end_date_type === 'half_day') {
                        return [
                            'is_affected' => true,
                            'half_slot' => $leave->end_half_slot,
                        ];
                    }
                }
                // For old half_day leaves
                elseif ($leave->leave_type === 'half_day') {
                    return [
                        'is_affected' => true,
                        'half_slot' => $leave->half_day_slot,
                    ];
                }
            }
        }

        return [
            'is_affected' => false,
            'half_slot' => null,
        ];
    }

    /**
     * Check if a time slot is during half-day leave
     *
     * @param  Carbon  $slotTime
     * @param  string|null  $halfSlot
     * @return bool
     */
    private function isSlotDuringHalfDayLeave($slotTime, $halfSlot)
    {
        if (! $halfSlot) {
            return false;
        }

        $noon = Carbon::parse('12:00');
        $slotHour = $slotTime->format('H:i');

        if ($halfSlot === 'morning') {
            // Morning slot: typically 9 AM to 1 PM (but adjust based on your schedule)
            return $slotHour >= '09:00' && $slotHour < '13:00';
        } elseif ($halfSlot === 'evening' || $halfSlot === 'afternoon' || $halfSlot === 'second_half') {
            // Evening/Afternoon slot: typically 2 PM to 6 PM
            return $slotHour >= '14:00' && $slotHour < '18:00';
        }

        return false;
    }

    public function isSlotAvailable($doctorId, $date, $time, $excludeAppointmentId = null)
    {
        $time24 = $this->convertTo24Hour($time);
        $selectedDate = Carbon::parse($date);
        $now = Carbon::now();

        if ($selectedDate->isToday()) {
            $slotDateTime = Carbon::parse($date.' '.$time24);
            if ($slotDateTime->lte($now->copy()->addMinutes(30))) {
                return false;
            }
        }

        $weekday = $selectedDate->dayOfWeek;
        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $weekday)
            ->where('is_available', true)
            ->first();

        if (! $schedule) {
            return false;
        }

        $slotTime = Carbon::parse($time24);
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        if ($slotTime->lt($startTime) || $slotTime->gte($endTime)) {
            return false;
        }

        // Check if doctor is on leave for this date/time
        $leaveCheck = $this->isDoctorOnLeave($doctorId, $date);
        if ($leaveCheck['on_leave']) {
            if ($leaveCheck['leave_type'] === 'full_day') {
                return false;
            }

            if ($leaveCheck['leave_type'] === 'custom' || $leaveCheck['leave_type'] === 'half_day') {
                $dateCheck = $this->isDateAffectedByHalfDay($selectedDate, $leaveCheck);
                if ($dateCheck['is_affected']) {
                    if ($this->isSlotDuringHalfDayLeave($slotTime, $dateCheck['half_slot'])) {
                        return false;
                    }
                }
            }
        }

        $query = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in', 'in_progress'])
            ->where(function ($q) use ($time24) {
                $q->where('appointment_time', $time24)
                    ->orWhere('appointment_time', $time24.':00');
            });

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return ! $query->exists();
    }

    private function convertTo24Hour($time)
    {
        if (! preg_match('/(AM|PM|am|pm)/', $time)) {
            return substr_count($time, ':') === 1 ? $time.':00' : $time;
        }

        try {
            return date('H:i:s', strtotime($time));
        } catch (\Exception $e) {
            return $time;
        }
    }

    public function validateAppointmentTime($doctorId, $date, $time, $excludeAppointmentId = null)
    {
        // Check if doctor is on leave
        $leaveCheck = $this->isDoctorOnLeave($doctorId, $date);
        if ($leaveCheck['on_leave']) {
            // For full-day leave, reject completely
            if ($leaveCheck['leave_type'] === 'full_day') {
                return [
                    'valid' => false,
                    'message' => $leaveCheck['message'],
                ];
            }

            // For custom or half-day leave, check if the specific date is affected
            if ($leaveCheck['leave_type'] === 'custom' || $leaveCheck['leave_type'] === 'half_day') {
                $selectedDate = Carbon::parse($date);
                $dateCheck = $this->isDateAffectedByHalfDay($selectedDate, $leaveCheck);

                if ($dateCheck['is_affected']) {
                    $slotTime = Carbon::parse($this->convertTo24Hour($time));
                    if ($this->isSlotDuringHalfDayLeave($slotTime, $dateCheck['half_slot'])) {
                        return [
                            'valid' => false,
                            'message' => 'This time slot is not available due to doctor\'s half-day leave. Please select another time.',
                        ];
                    }
                }
            }
        }

        // Check if appointment date is in the past
        $selectedDate = Carbon::parse($date);
        $now = Carbon::now();

        if ($selectedDate->lt($now->startOfDay())) {
            return [
                'valid' => false,
                'message' => 'Cannot book appointments for past dates.',
            ];
        }

        // Check if time has passed for today
        if ($selectedDate->isToday()) {
            $time24 = $this->convertTo24Hour($time);
            $slotDateTime = Carbon::parse($date.' '.$time24);

            // Add 30-minute buffer to prevent booking slots that are too close to current time
            if ($slotDateTime->lte($now->copy()->addMinutes(30))) {
                return [
                    'valid' => false,
                    'message' => 'Cannot book appointments for past time slots or slots within 30 minutes. Please select a future time.',
                ];
            }
        }

        if (! $this->isSlotAvailable($doctorId, $date, $time, $excludeAppointmentId)) {
            return [
                'valid' => false,
                'message' => 'This time slot is not available. Please select another time.',
            ];
        }

        return [
            'valid' => true,
            'message' => 'Time slot is available',
        ];
    }

    /**
     * Get the next available date after doctor's leave ends
     *
     * @param  int  $doctorId
     * @param  string  $currentDate
     * @return string|null
     */
    public function getNextAvailableDate($doctorId, $currentDate)
    {
        $current = Carbon::parse($currentDate);

        // Check leave for current date
        $leaveCheck = $this->isDoctorOnLeave($doctorId, $current->format('Y-m-d'));

        if ($leaveCheck['on_leave'] && $leaveCheck['leave_type'] === 'full_day') {
            // If doctor is on full-day leave, find the next day after leave ends
            $leaveEndDate = Carbon::parse($leaveCheck['leave_end_date']);

            // Check the day after leave ends
            $nextDate = $leaveEndDate->copy()->addDay();
            $nextLeaveCheck = $this->isDoctorOnLeave($doctorId, $nextDate->format('Y-m-d'));

            // Keep checking until we find a day when doctor is not on full-day leave
            while ($nextLeaveCheck['on_leave'] && $nextLeaveCheck['leave_type'] === 'full_day') {
                $nextDate->addDay();
                $nextLeaveCheck = $this->isDoctorOnLeave($doctorId, $nextDate->format('Y-m-d'));
            }

            return $nextDate->format('Y-m-d');
        }

        return null;
    }
}
