<?php

namespace App\Services\Admin;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class CalendarService
{
    /**
     * Get calendar data for a specific month
     */
    public function getCalendarData($year, $month, $doctorId = null)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $today = Carbon::today()->format('Y-m-d');

        // Get all appointments for this month
        $query = Appointment::whereBetween('appointment_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with(['patient', 'doctor.doctorProfile']);

        // Filter by doctor if specified
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $appointments = $query->get()
            ->groupBy(function ($appointment) {
                return Carbon::parse($appointment->appointment_date)->format('Y-m-d');
            });

        // Generate calendar days
        $calendarDays = [];

        // Start from the first day of the week that the month starts on
        $calendarStart = $startDate->copy()->startOfWeek(); // Sunday
        $calendarEnd = $endDate->copy()->endOfWeek(); // Saturday

        $currentDate = $calendarStart->copy();

        while ($currentDate <= $calendarEnd) {
            $dateKey = $currentDate->format('Y-m-d');
            $isCurrentMonth = $currentDate->month == $month;

            $dayAppointments = [];

            if ($isCurrentMonth && isset($appointments[$dateKey])) {
                $dayAppointments = $appointments[$dateKey]->map(function ($apt) {
                    // Skip appointments without patients or doctors
                    if (!$apt->patient || !$apt->doctor) {
                        return null;
                    }

                    return [
                        'id' => $apt->id,
                        'time' => Carbon::parse($apt->appointment_time)->format('g:i A'),
                        'status' => $apt->status,
                        'patient_name' => $apt->patient->first_name . ' ' . $apt->patient->last_name,
                        'doctor_name' => 'Dr. ' . $apt->doctor->last_name,
                        'doctor_short' => 'Dr. ' . substr($apt->doctor->last_name, 0, 1) . '.',
                    ];
                })->filter()->values()->toArray();
            }

            $calendarDays[] = [
                'day' => $currentDate->day,
                'date' => $dateKey,
                'is_current_month' => $isCurrentMonth,
                'is_today' => $dateKey === $today,
                'appointments' => $dayAppointments,
            ];

            $currentDate->addDay();
        }

        return [
            'days' => $calendarDays,
            'month_name' => $startDate->format('F Y'),
            'year' => $year,
            'month' => $month,
            'prev_month' => $startDate->copy()->subMonth()->format('Y-m'),
            'next_month' => $startDate->copy()->addMonth()->format('Y-m'),
        ];
    }

    /**
     * Get all doctors for filter dropdown
     */
    public function getAllDoctors()
    {
        return User::where('role', 'doctor')
            ->with('doctorProfile.specialty')
            ->orderBy('first_name')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => 'Dr. ' . $doctor->first_name . ' ' . $doctor->last_name,
                    'specialty' => $doctor->doctorProfile?->specialty?->name ?? 'General',
                ];
            });
    }

    /**
     * Get appointments for a specific date
     */
    public function getDateAppointments($date, $doctorId = null)
    {
        $query = Appointment::with(['patient', 'doctor.doctorProfile.specialty'])
            ->whereDate('appointment_date', $date)
            ->orderBy('appointment_time');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $appointments = $query->get();

        return $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'appointment_number' => $appointment->appointment_number,
                'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                'doctor_name' => 'Dr. ' . $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name,
                'specialty' => $appointment->doctor->doctorProfile?->specialty?->name ?? 'General',
                'time' => Carbon::parse($appointment->appointment_time)->format('g:i A'),
                'status' => $appointment->status,
                'reason' => $appointment->reason_for_visit,
                'type' => ucfirst(str_replace('_', ' ', $appointment->appointment_type)),
            ];
        });
    }

    /**
     * Get appointment statistics for the month
     */
    public function getMonthStatistics($year, $month, $doctorId = null)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query = Appointment::whereBetween('appointment_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ]);

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $appointments = $query->get();

        return [
            'total' => $appointments->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
        ];
    }
}
