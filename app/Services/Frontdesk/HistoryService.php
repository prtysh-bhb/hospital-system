<?php

namespace App\services\frontdesk;

use App\Models\Appointment;

class HistoryService
{
    /**
     * Get appointments history with filters
     */
    public function getAppointmentsHistory($filters = [])
    {
        $query = Appointment::with(['patient', 'doctor', 'patient.patientProfile', 'doctor.doctorProfile'])
            ->whereIn('status', ['completed', 'cancelled', 'no_show'])
            ->whereHas('patient')  // Only get appointments where patient exists
            ->whereHas('doctor');  // Only get appointments where doctor exists

        // Date range filter
        if (isset($filters['from_date']) && ! empty($filters['from_date'])) {
            $query->where('appointment_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date']) && ! empty($filters['to_date'])) {
            $query->where('appointment_date', '<=', $filters['to_date']);
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // Search filter (patient or doctor name)
        if (isset($filters['search']) && ! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('doctor', function ($dq) use ($search) {
                        $dq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(10);
    }

    /**
     * Get statistics for appointment history
     */
    public function getStatistics($filters = [])
    {
        $query = Appointment::whereIn('status', ['completed', 'cancelled', 'no_show']);

        // Apply date range if provided
        if (isset($filters['from_date'])) {
            $query->where('appointment_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('appointment_date', '<=', $filters['to_date']);
        }

        $total = $query->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $cancelled = (clone $query)->where('status', 'cancelled')->count();
        $noShow = (clone $query)->where('status', 'no_show')->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'completed_percentage' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            'cancelled' => $cancelled,
            'cancelled_percentage' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0,
            'no_show' => $noShow,
            'no_show_percentage' => $total > 0 ? round(($noShow / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Get appointment details by ID
     */
    public function getAppointmentDetails($id)
    {
        return Appointment::with([
            'patient.patientProfile',
            'doctor.doctorProfile',
            'prescriptions',
        ])->findOrFail($id);
    }
}
