<?php

namespace App\Http\Controllers\Frontdesk;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentListController extends Controller
{
    /**
     * List all appointments
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor', 'patient']);

        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('doctor')) {
            $query->where('doctor_id', $request->doctor);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('appointment_type', $request->type);
        }

        $appointments = $query
            ->orderBy('appointment_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $doctors = User::where('role', 'doctor')->orderBy('first_name')->get();

        return view('frontdesk.index', compact('appointments', 'doctors'));
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        // ✅ If already same status, silently accept (IMPORTANT)
        if ($appointment->status === $request->status) {
            return response()->json([
                'success' => true,
                'status' => $appointment->status,
                'label' => ucfirst($appointment->status),
            ]);
        }

        // Hard lock
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'error' => 'Appointment is already closed.',
            ], 409); // ❗ 409 instead of 403
        }

        // Allowed transitions
        $allowed = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled'],
        ];

        if (
            ! isset($allowed[$appointment->status]) ||
            ! in_array($request->status, $allowed[$appointment->status])
        ) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid status change.',
            ], 422);
        }

        $appointment->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'status' => $appointment->status,
            'label' => ucfirst($appointment->status),
        ]);
    }
}
