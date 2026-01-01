<?php

namespace App\Http\Controllers\Frontdesk;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentListController extends Controller
{
    /**
     * List all appointments
     */
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'patient'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('frontdesk.index', compact('appointments'));
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
            'status'  => $appointment->status,
            'label'   => ucfirst($appointment->status),
        ]);
    }

    // Hard lock
    if (in_array($appointment->status, ['completed', 'cancelled'])) {
        return response()->json([
            'success' => false,
            'error'   => 'Appointment is already closed.',
        ], 409); // ❗ 409 instead of 403
    }

    // Allowed transitions
    $allowed = [
        'pending'   => ['confirmed', 'cancelled'],
        'confirmed' => ['completed', 'cancelled'],
    ];

    if (
        ! isset($allowed[$appointment->status]) ||
        ! in_array($request->status, $allowed[$appointment->status])
    ) {
        return response()->json([
            'success' => false,
            'error'   => 'Invalid status change.',
        ], 422);
    }

    $appointment->update([
        'status' => $request->status,
    ]);

    return response()->json([
        'success' => true,
        'status'  => $appointment->status,
        'label'   => ucfirst($appointment->status),
    ]);
}

}
