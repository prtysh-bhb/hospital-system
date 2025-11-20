<?php

namespace App\Services;

use App\Models\DoctorSchedule;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentSlotService
{
    /**
     * Get available time slots for a doctor on a specific date
     *
     * @param int $doctorId
     * @param string $date
     * @return array
     */
    public function getAvailableSlots($doctorId, $date)
    {
        if (!$doctorId || !$date) {
            return [
                'success' => false,
                'message' => 'Doctor and date are required',
                'slots' => []
            ];
        }

        $weekday = Carbon::parse($date)->dayOfWeek;
        $selectedDate = Carbon::parse($date);
        $now = Carbon::now();
        $isToday = $selectedDate->isToday();

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $weekday)
            ->where('is_available', true)
            ->first();

        $slots = [];
        if ($schedule) {
            $start = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);
            $slotDuration = $schedule->slot_duration ?? 30;

            // Get booked appointments for this doctor on this date
            $bookedSlots = Appointment::where('doctor_id', $doctorId)
                ->where('appointment_date', $date)
                ->whereIn('status', ['pending', 'confirmed', 'checked_in', 'in_progress'])
                ->pluck('appointment_time')
                ->map(function($time) {
                    try {
                        return Carbon::parse($time)->format('H:i');
                    } catch (\Exception $e) {
                        return null;
                    }
                })
                ->filter()
                ->toArray();

            while ($start < $end) {
                $timeSlot = $start->format('H:i');
                
                // If today, skip slots that are in the past or current hour
                if ($isToday) {
                    $slotDateTime = Carbon::parse($date . ' ' . $timeSlot);
                    // Skip if slot time has passed or is within current hour
                    if ($slotDateTime->lte($now)) {
                        $start->addMinutes($slotDuration);
                        continue;
                    }
                }
                
                // Only add slot if not booked
                if (!in_array($timeSlot, $bookedSlots)) {
                    $slots[] = $start->format('h:i A');
                }
                $start->addMinutes($slotDuration);
            }
        }

        return [
            'success' => true,
            'slots' => $slots,
            'message' => count($slots) > 0 ? 'Slots available' : 'No slots available'
        ];
    }

    /**
     * Check if a specific time slot is available
     *
     * @param int $doctorId
     * @param string $date
     * @param string $time (format: "09:00 AM" or "09:00")
     * @param int|null $excludeAppointmentId - exclude this appointment when checking (for updates)
     * @return bool
     */
    public function isSlotAvailable($doctorId, $date, $time, $excludeAppointmentId = null)
    {
        // Convert time to 24-hour format
        $time24 = $this->convertTo24Hour($time);

        // Check if appointment is for today and time has passed
        $selectedDate = Carbon::parse($date);
        $now = Carbon::now();
        
        if ($selectedDate->isToday()) {
            $slotDateTime = Carbon::parse($date . ' ' . $time24);
            // Reject if slot time has passed or is within current hour
            if ($slotDateTime->lte($now)) {
                return false;
            }
        }

        // Check if slot exists in doctor's schedule
        $weekday = Carbon::parse($date)->dayOfWeek;
        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $weekday)
            ->where('is_available', true)
            ->first();

        if (!$schedule) {
            return false;
        }

        // Check if time is within schedule range
        $slotTime = Carbon::parse($time24);
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        if ($slotTime->lt($startTime) || $slotTime->gte($endTime)) {
            return false;
        }

        // Check if slot is already booked
        $query = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in', 'in_progress'])
            ->where(function($q) use ($time24) {
                $q->where('appointment_time', $time24)
                  ->orWhere('appointment_time', $time24 . ':00');
            });

        // Exclude specific appointment (for updates)
        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return !$query->exists();
    }

    /**
     * Convert time to 24-hour format
     *
     * @param string $time
     * @return string
     */
    private function convertTo24Hour($time)
    {
        // If already in 24-hour format (HH:MM or HH:MM:SS)
        if (!preg_match('/(AM|PM|am|pm)/', $time)) {
            // Add seconds if not present
            if (substr_count($time, ':') == 1) {
                return $time . ':00';
            }
            return $time;
        }

        // Convert 12-hour to 24-hour
        try {
            return date('H:i:s', strtotime($time));
        } catch (\Exception $e) {
            return $time;
        }
    }

    /**
     * Validate appointment time before creation/update
     *
     * @param int $doctorId
     * @param string $date
     * @param string $time
     * @param int|null $excludeAppointmentId
     * @return array
     */
    public function validateAppointmentTime($doctorId, $date, $time, $excludeAppointmentId = null)
    {
        // Check if appointment date is in the past
        $selectedDate = Carbon::parse($date);
        $now = Carbon::now();
        
        if ($selectedDate->lt($now->startOfDay())) {
            return [
                'valid' => false,
                'message' => 'Cannot book appointments for past dates.'
            ];
        }

        // Check if time has passed for today
        if ($selectedDate->isToday()) {
            $time24 = $this->convertTo24Hour($time);
            $slotDateTime = Carbon::parse($date . ' ' . $time24);
            
            if ($slotDateTime->lte($now)) {
                return [
                    'valid' => false,
                    'message' => 'Cannot book appointments for past time slots. Please select a future time.'
                ];
            }
        }

        if (!$this->isSlotAvailable($doctorId, $date, $time, $excludeAppointmentId)) {
            return [
                'valid' => false,
                'message' => 'This time slot is not available. Please select another time.'
            ];
        }

        return [
            'valid' => true,
            'message' => 'Time slot is available'
        ];
    }
}
