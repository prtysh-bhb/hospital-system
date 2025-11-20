<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('admin.appointments', compact('doctors'));
    }
    public function addAppointments()
    {
        $patients = User::where('role', 'patient')->get();
        return view('admin.add-appointment', compact('patients'));
    }
    public function getAppointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.doctorProfile.specialty']);

        // 1. SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('appointment_number', 'LIKE', "%$search%")
                    ->orWhereHas('patient', function ($pat) use ($search) {
                        $pat->where('first_name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%")
                            ->orWhere('phone', 'LIKE', "%$search%");
                    });
            });
        }

        // 2. DOCTOR
        if ($request->filled('doctor_id') && $request->doctor_id != '') {
            $query->where('doctor_id', $request->doctor_id);
        }

        // 3. DATE
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // 4. STATUS
        if ($request->filled('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
}
