<?php

namespace App\Http\Controllers\Doctor;

use App\Events\NotifiyUserEvent;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorLeave;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;

class DoctorLeaveController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $leaves = DoctorLeave::where('doctor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('doctor.leaves.create', compact('leaves'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'leave_type' => 'required|in:full_day,half_day,custom',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'half_day_slot' => 'nullable|required_if:leave_type,half_day|in:morning,evening',
            'start_time' => 'nullable|required_if:leave_type,custom|date_format:H:i',
            'end_time' => 'nullable|required_if:leave_type,custom|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:255',
            'cancel_appointments' => 'nullable|boolean',
        ]);

        if ($this->hasOverlappingLeave(
            $user->id,
            $request->input('start_date'),
            $request->input('end_date')
        )) {

            return response()->json([
                'success' => false,
                'type' => 'leave_conflict',
                'message' => 'You already have a leave applied for the selected date(s).',
                'errors' => [
                    'leave_dates' => ['You already have a leave applied for the selected date(s).'],
                ],
            ], 422);
        }
        $cancelAppointments = $request->boolean('cancel_appointments', false);

        $conflictingAppointments = Appointment::where('doctor_id', $user->id)
            ->whereBetween('appointment_date', [$request->input('start_date'), $request->input('end_date')])
            ->whereNotIn('status', ['cancelled', 'completed']);

        if ($conflictingAppointments->exists() && ! $cancelAppointments) {
            $count = $conflictingAppointments->count();

            return response()->json([
                'success' => false,
                'type' => 'appointment_conflict',
                'message' => "You have {$count} appointment(s) scheduled during the selected leave period.",
                'appointment_count' => $count,
            ], 409);

        }

        try {
            if ($cancelAppointments) {
                Appointment::where('doctor_id', $user->id)
                    ->whereBetween('appointment_date', [$request->input('start_date'), $request->input('end_date')])
                    ->whereNotIn('status', ['cancelled', 'completed'])
                    ->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancellation_reason' => 'Doctor on leave: '.($request->input('reason') ?? 'Leave approved'),
                    ]);
                event(new NotifiyUserEvent(
                    $user->id,
                    $request->input('reason')
                ));
            }
            DoctorLeave::create([
                'doctor_id' => $user->id,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'leave_type' => $request->input('leave_type'),
                'half_day_slot' => $request->input('half_day_slot'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'reason' => $request->input('reason'),
                'status' => 'approved',
            ]);

            DoctorProfile::where('user_id', $user->id)->update(['available_for_booking' => false]);

            $message = 'Leave request submitted successfully.';
            if ($cancelAppointments) {
                $message = 'Leave request submitted and conflicting appointments have been cancelled.';
            }

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to submit leave request.'], 500);
        }
    }

    // check if a doctor has overlapping leave
    private function hasOverlappingLeave(
        int $doctorId,
        string $startDate,
        string $endDate
    ): bool {
        return DoctorLeave::where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            })
            ->exists();
    }
}
