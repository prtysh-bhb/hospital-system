<?php

namespace App\Services;

use App\Models\DoctorProfile;

class DoctoreServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        

    }
    public function getDoctors()
    {
        return DoctorProfile::with('specialty')->get();
    }
}
