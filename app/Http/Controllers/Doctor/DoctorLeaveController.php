<?php

namespace App\Http\Controllers\Doctor;

use App\Enums\WhatsappTemplating;
use App\Events\NotifiyUserEvent;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorLeave;
use App\Models\DoctorProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'leave_type' => 'required|in:full_day,custom',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_half_select' => 'required_if:leave_type,custom|in:full_day,first_half,second_half',
            'end_half_select' => 'required_if:leave_type,custom|in:full_day,first_half,second_half',
            'reason' => 'required|string|max:500',
            'approval_type' => 'nullable|in:admin,auto|default:admin',
            'is_adhoc' => 'nullable|boolean',
        ], [
            'start_date.after_or_equal' => 'The start date must be a date after or equal to today.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to start date.',
            'start_half_select.required_if' => 'Start half selection is required for custom leave.',
            'end_half_select.required_if' => 'End half selection is required for custom leave.',
            'reason.required' => 'Please provide a reason for your leave.',
            'approval_type.in' => 'Invalid approval type selected.',
        ]);

        // Check overlapping leave
        if ($this->hasOverlappingLeave($user->id, $request->input('start_date'), $request->input('end_date'))) {
            return response()->json([
                'success' => false,
                'type' => 'leave_conflict',
                'message' => 'You already have a leave applied for the selected date(s). Please check your leave history.',
                'toast_message' => 'You already have a leave applied for the selected date(s).',
                'errors' => [
                    'leave_dates' => ['You already have a leave applied for the selected date(s).'],
                ],
            ], 200);
        }

        $isAdhoc = $request->boolean('is_adhoc', false);

        // Determine approval type: If adhoc is checked, it's auto; otherwise use the selected approval_type
        $approvalType = $isAdhoc ? 'auto' : $request->input('approval_type', 'admin');

        // Check for conflicting appointments
        $conflictingAppointments = Appointment::where('doctor_id', $user->id)
            ->whereBetween('appointment_date', [
                $request->input('start_date'),
                $request->input('end_date'),
            ])
            ->whereNotIn('status', ['cancelled', 'completed']);

        // Only show conflict if approval_type is auto and there are appointments, unless forced cancel
        $forceCancel = $request->boolean('cancel_appointments', false);
        if ($approvalType === 'auto' && $conflictingAppointments->exists() && ! $forceCancel) {
            $count = $conflictingAppointments->count();
            $appointmentDetails = $conflictingAppointments->take(5)->get()->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'appointment_date' => $appt->appointment_date->format('d M, Y'),
                    'patient_name' => $appt->patient->first_name.' '.$appt->patient->last_name,
                    'status' => $appt->status,
                ];
            });

            return response()->json([
                'success' => false,
                'type' => 'appointment_conflict',
                'message' => "You have {$count} appointment(s) scheduled during the selected leave period.",
                'appointment_count' => $count,
                'appointments' => $appointmentDetails,
            ], 409);
        }

        DB::beginTransaction();
        try {
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));
            $leaveType = $request->input('leave_type');

            // Prepare base leave data
            $leaveData = [
                'doctor_id' => $user->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'leave_type' => $leaveType,
                'reason' => $request->input('reason'),
                'approval_type' => $approvalType,
                'is_adhoc' => $isAdhoc,
            ];

            // Map select values to database fields for custom leave
            if ($leaveType === 'custom') {
                // Start date mapping
                $startHalfSelect = $request->input('start_half_select');
                if ($startHalfSelect === 'full_day') {
                    $leaveData['start_date_type'] = 'full_day';
                    $leaveData['start_half_slot'] = 'morning'; // default
                } else {
                    $leaveData['start_date_type'] = 'half_day';
                    $leaveData['start_half_slot'] = $startHalfSelect === 'first_half' ? 'morning' : 'evening';
                }

                // End date mapping
                $endHalfSelect = $request->input('end_half_select');
                if ($endHalfSelect === 'full_day') {
                    $leaveData['end_date_type'] = 'full_day';
                    $leaveData['end_half_slot'] = 'morning'; // default
                } else {
                    $leaveData['end_date_type'] = 'half_day';
                    $leaveData['end_half_slot'] = $endHalfSelect === 'first_half' ? 'morning' : 'evening';
                }

                // Handle legacy field for backward compatibility
                $leaveData['half_day_slot'] = $leaveData['start_half_slot'] ?? 'morning';
            } else {
                // Full day leave - set all to full_day
                $leaveData['start_date_type'] = 'full_day';
                $leaveData['start_half_slot'] = 'morning';
                $leaveData['end_date_type'] = 'full_day';
                $leaveData['end_half_slot'] = 'morning';
                $leaveData['half_day_slot'] = 'morning'; // legacy field
            }

            // Decide leave status based on approval type
            $status = ($approvalType === 'admin') ? 'pending' : 'approved';
            $leaveData['status'] = $status;

            // For custom leave, store as single record with all date/type information
            $totalDays = $startDate->diffInDays($endDate) + 1;
            $leaveRecords = [];

            if ($leaveType === 'full_day') {
                // Full day leave - create one record
                $leaveRecords[] = $leaveData;
            } else {
                // Custom leave type - always store as single record
                // The database schema supports storing start_date_type, start_half_slot, end_date_type, end_half_slot
                // So we can represent the entire leave period in one record
                $leaveRecords[] = $leaveData;
            }

            // Create leave records
            $createdLeaves = [];
            foreach ($leaveRecords as $record) {
                $createdLeaves[] = DoctorLeave::create($record);
            }

            // If auto-approved, cancel all conflicting appointments
            if ($approvalType === 'auto') {
                $appointments = $conflictingAppointments->get();
                foreach ($appointments as $appointment) {
                    $appointment->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancellation_reason' => 'Doctor on leave: '.($request->input('reason') ?? 'Leave approved'),
                    ]);

                    // Notify patient
                    $patient = $appointment->patient;
                    $doctor = $appointment->doctor;

                    if ($patient && $patient->phone) {
                        $doctorName = 'Dr. '.trim($doctor->first_name.' '.$doctor->last_name);
                        $patientName = trim($patient->first_name.' '.$patient->last_name);

                        $leaveStartDate = $startDate->format('F j, Y');
                        $leaveEndDate = $endDate->format('F j, Y');

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
            }

            // Disable booking only if leave is approved
            if ($status === 'approved') {
                DoctorProfile::where('user_id', $user->id)
                    ->update(['available_for_booking' => false]);
            }

            DB::commit();

            // Prepare response message
            $message = $status === 'pending'
                ? 'Leave request submitted for admin approval.'
                : 'Leave request submitted successfully.';

            if ($status === 'approved' && $conflictingAppointments->count() > 0) {
                $message = 'Leave request submitted and '.$conflictingAppointments->count().' appointment(s) have been cancelled.';
            }

            if ($isAdhoc) {
                $message = 'Adhoc leave request submitted successfully. Your leave is auto-approved.';
                if ($conflictingAppointments->count() > 0) {
                    $message = 'Adhoc leave request submitted. '.$conflictingAppointments->count().' appointment(s) have been cancelled.';
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'records_created' => count($leaveRecords),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to submit doctor leave: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit leave request. Please try again.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
            ], 500);
        }
    }

    // Check if a doctor has overlapping leave
    private function hasOverlappingLeave(int $doctorId, string $startDate, string $endDate): bool
    {
        return DoctorLeave::where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            })
            ->exists();
    }
}
