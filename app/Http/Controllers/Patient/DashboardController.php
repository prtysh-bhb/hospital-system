<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get appointments with all related data
        $appointments = $user->patientAppointments()
            ->with([
                'doctor.doctorProfile.specialty',
                'prescriptions',
            ])
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function ($appointment) {
                // Get doctor info
                $doctor = $appointment->doctor;
                $doctorProfile = $doctor->doctorProfile ?? null;
                $specialty = $doctorProfile?->specialty;

                // Get prescription if exists
                $prescription = $appointment->prescriptions->first();

                return (object) [
                    'id' => $appointment->id,
                    'appointment_number' => $appointment->appointment_number,
                    'doctor_id' => $doctor->id,
                    'doctor_name' => 'Dr. '.($doctor->full_name ?? 'Unknown'),
                    'doctor_image' => $doctor->profile_image,
                    'specialty' => $specialty?->name ?? 'General Medicine',
                    'specialty_id' => $specialty?->id,
                    'qualification' => $doctorProfile?->qualification ?? '',
                    'consultation_fee' => $doctorProfile?->consultation_fee ?? 0,
                    'date' => Carbon::parse($appointment->appointment_date),
                    'time' => Carbon::parse($appointment->appointment_time),
                    'duration' => $appointment->duration_minutes ?? 30,
                    'status' => $appointment->status,
                    'appointment_type' => $appointment->appointment_type ?? 'consultation',
                    'reason_for_visit' => $appointment->reason_for_visit,
                    'symptoms' => $appointment->symptoms,
                    'notes' => $appointment->notes,
                    'cancellation_reason' => $appointment->cancellation_reason,
                    // Prescription data
                    'has_prescription' => $prescription !== null,
                    'prescription' => $prescription ? (object) [
                        'id' => $prescription->id,
                        'prescription_number' => $prescription->prescription_number,
                        'diagnosis' => $prescription->diagnosis,
                        'medications' => $prescription->medications ?? [],
                        'instructions' => $prescription->instructions,
                        'follow_up_date' => $prescription->follow_up_date ? Carbon::parse($prescription->follow_up_date)->format('F j, Y') : null,
                        'notes' => $prescription->notes,
                    ] : null,
                ];
            });

        // Calculate stats
        $today = Carbon::today();
        $stats = (object) [
            'total' => $appointments->count(),
            'today' => $appointments->filter(fn ($a) => $a->date->isSameDay($today))->count(),
            'upcoming' => $appointments->filter(fn ($a) => $a->date->gte($today) && in_array($a->status, ['pending', 'confirmed']))->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
        ];

        return view('patient.dashboard', compact('appointments', 'stats'));
    }

    public function cancelAppointment(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'appointment_id' => 'required|exists:appointments,id',
                'cancellation_reason' => 'required|string|min:10|max:500',
            ], [
                'cancellation_reason.required' => 'Please provide a reason for cancellation.',
                'cancellation_reason.min' => 'Cancellation reason must be at least 10 characters.',
                'cancellation_reason.max' => 'Cancellation reason cannot exceed 500 characters.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            $appointment = Appointment::findOrFail($request->appointment_id);

            // Verify the appointment belongs to the logged-in patient
            if ($appointment->patient_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to cancel this appointment.',
                ], 403);
            }

            // Check if appointment can be cancelled
            if (! in_array($appointment->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending or confirmed appointments can be cancelled.',
                ], 400);
            }

            // Update appointment status
            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = $request->cancellation_reason;
            $appointment->cancelled_at = now();
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully!',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found.',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Cancel appointment error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while cancelling the appointment. Please try again.',
            ], 500);
        }
    }
}
