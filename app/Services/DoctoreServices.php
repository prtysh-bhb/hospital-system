<?php

namespace App\Services;

use App\Models\DoctorProfile;

class DoctoreServices
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getDoctors($filters = [])
    {
        $query = DoctorProfile::with('specialty', 'user');

        // Search by name, email, or phone
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhereHas('specialty', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by specialty
        if (!empty($filters['specialty_id'])) {
            $query->where('specialty_id', $filters['specialty_id']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            });
        }

        return $query->get();
    }
}
