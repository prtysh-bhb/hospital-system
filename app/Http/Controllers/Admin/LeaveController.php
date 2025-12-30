<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Appointment;
use App\Models\DoctorLeave;
use Illuminate\Http\Request;
use App\Models\DoctorProfile;
use App\Events\NotifiyUserEvent;
use App\Enums\WhatsappTemplating;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = DoctorLeave::with('doctor');

        // Doctor filter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Leave type filter
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        // Date range filter 
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            });
        } elseif ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(10);
        $doctors = User::where('role', 'doctor')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $leaves->items(),
                'pagination' => [
                    'current_page' => $leaves->currentPage(),
                    'last_page' => $leaves->lastPage(),
                    'per_page' => $leaves->perPage(),
                    'total' => $leaves->total(),
                    'from' => $leaves->firstItem(),
                    'to' => $leaves->lastItem(),
                ],
            ]);
        }

        return view('admin.leave.index', compact('leaves', 'doctors'));
    }
    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'leave_id' => 'required|exists:doctor_leaves,id',
                'status' => 'required|in:pending,approved,rejected,cancelled',
            ]);

            $leave = DoctorLeave::findOrFail($request->leave_id);
            $leave->status = $request->status;

            // Set approved_by only when approved
            if ($request->status === 'approved') {
                $leave->approved_by = auth()->id();
            } else {
                $leave->approved_by = null;
            }

            $leave->save();

            // If status is approved, cancel all conflicting appointments
            if ($request->status === 'approved') {
                $conflictingAppointments = Appointment::where('doctor_id', $leave->doctor_id)
                    ->whereBetween('appointment_date', [$leave->start_date, $leave->end_date])
                    ->whereNotIn('status', ['cancelled', 'completed'])
                    ->get();

                foreach ($conflictingAppointments as $appointment) {
                    $appointment->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancellation_reason' => 'Doctor on leave: ' . ($leave->reason ?? 'Leave approved by admin'),
                    ]);

                    $patient = $appointment->patient;
                    $doctor = $appointment->doctor;

                    // Notify patient if phone exists
                    if ($patient && $patient->phone) {
                        $doctorName = 'Dr. ' . trim($doctor->first_name . ' ' . $doctor->last_name);
                        $patientName = trim($patient->first_name . ' ' . $patient->last_name);

                        $leaveStartDate = Carbon::parse($leave->start_date)->format('F j, Y');
                        $leaveEndDate = Carbon::parse($leave->end_date)->format('F j, Y');

                        $bookingLink = route('booking');

                        $components = [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['key' => 'name', 'type' => 'text', 'text' => $patientName],
                                    ['key' => 'doctor_name', 'type' => 'text', 'text' => $doctorName],
                                    ['key' => 'start_date', 'type' => 'text', 'text' => $leaveStartDate],
                                    ['key' => 'end_date', 'type' => 'text', 'text' => $leaveEndDate],
                                    ['key' => 'booking_link', 'type' => 'text', 'text' => $bookingLink],
                                ],
                            ],
                        ];

                        $params = [
                            'phone_number' => $patient->phone,
                            'template_name' => WhatsappTemplating::DOCTOR_ON_LEAVE->value,
                            'components' => $components,
                        ];

                        event(new NotifiyUserEvent($params));
                    }
                }

                // Optionally, disable doctor booking
                DoctorProfile::where('user_id', $leave->doctor_id)
                    ->update(['available_for_booking' => false]);
            }

            $statusText = str_replace('_', ' ', $request->status);
            $message = "Leave has been {$statusText} successfully.";

            // fresh counts (without page reload)
            $counts = [
                'total' => DoctorLeave::count(),
                'pending' => DoctorLeave::where('status', 'pending')->count(),
                'approved' => DoctorLeave::where('status', 'approved')->count(),
                'rejected' => DoctorLeave::where('status', 'rejected')->count(),
                'cancelled' => DoctorLeave::where('status', 'cancelled')->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => $message,
                'counts' => $counts,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
