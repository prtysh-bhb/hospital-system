<?php

namespace App\Services\admin;

use App\Models\PatientProfile;

class PetientService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getPatients($filters = [])
    {
        $query = PatientProfile::with('user');

        // Search by name, email, or phone
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by blood group
        if (!empty($filters['blood_group'])) {
            $query->where('blood_group', $filters['blood_group']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            });
        }

        return $query->paginate(10);
    }
}
