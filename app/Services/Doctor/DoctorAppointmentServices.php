<?php

namespace App\Services\Doctor;

class DoctorAppointmentServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Return today's appointments for a doctor with optional filters.
     * 
     * @param int $doctorId
     * @param array $filters ['status' => string, 'search' => string, 'date' => Y-m-d]
    * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getTodayAppointments(int $doctorId, array $filters = [])
    {
        $query = \App\Models\Appointment::with(['patient.patientProfile'])
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $filters['date'] ?? now()->toDateString())
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'asc');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%");
                })->orWhere('appointment_number', 'like', "%{$search}%");
            });
        }

        // Return the query builder so callers can paginate
        return $query;
    }
}
