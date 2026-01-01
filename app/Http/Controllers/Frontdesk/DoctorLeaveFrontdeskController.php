<?php

namespace App\Http\Controllers\Frontdesk;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorLeave;
use App\Models\DoctorProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DoctorLeaveFrontdeskController extends Controller
{
    public function index()
    {
        $leaves = DoctorLeave::with('doctor')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        $doctors = User::whereHas('doctorProfile')->get();

        return view('frontdesk.doctor_leaves.index', compact('leaves', 'doctors'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id',
            'leave_type' => 'required|in:full_day,custom',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_half_select' => 'required_if:leave_type,custom|in:full_day,first_half,second_half',
            'end_half_select' => 'required_if:leave_type,custom|in:full_day,first_half,second_half',
            'reason' => 'required|string|max:500',
            'is_adhoc' => 'nullable|boolean',
            'force' => 'nullable|boolean',
        ], [
            'start_date.after_or_equal' => 'The start date must be a date after or equal to today.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to start date.',
            'start_half_select.required_if' => 'Start half selection is required for custom leave.',
            'end_half_select.required_if' => 'End half selection is required for custom leave.',
            'reason.required' => 'Please provide a reason for the leave.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $doctorId = $validated['doctor_id'];
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $leaveType = $validated['leave_type'];

        // Check for overlapping leave
        if ($this->hasOverlappingLeave($doctorId, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'))) {
            return response()->json([
                'success' => false,
                'type' => 'leave_conflict',
                'message' => 'Doctor already has a leave for the selected dates.',
            ], 422);
        }

        // Check for conflicting appointments
        $conflictingAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('appointment_date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->whereNotIn('status', ['cancelled', 'completed']);

        if ($conflictingAppointments->exists() && empty($validated['force'])) {
            $count = $conflictingAppointments->count();
            $appointmentDetails = $conflictingAppointments->take(5)->get()->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'appointment_date' => $appt->appointment_date->format('d M, Y'),
                    'patient_name' => $appt->patient->first_name . ' ' . $appt->patient->last_name,
                    'status' => $appt->status,
                ];
            });

            return response()->json([
                'success' => false,
                'type' => 'appointment_conflict',
                'message' => "There are {$count} appointment(s) scheduled during the selected leave period.",
                'appointment_count' => $count,
                'appointments' => $appointmentDetails,
            ], 409);
        }

        DB::beginTransaction();
        try {
            // If force, cancel all conflicting appointments
            if ($conflictingAppointments->exists() && !empty($validated['force'])) {
                $appointments = $conflictingAppointments->get();
                foreach ($appointments as $appointment) {
                    $appointment->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancellation_reason' => 'Doctor on leave (frontdesk action): ' . ($validated['reason'] ?? 'Leave approved'),
                    ]);
                }
            }

            $leaveData = [
                'doctor_id' => $doctorId,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'leave_type' => $leaveType,
                'reason' => $validated['reason'],
                'status' => $validated['is_adhoc'] ?? false ? 'approved' : 'pending',
                'approval_type' => 'frontdesk',
                'approved_by' => auth()->id(),
                'is_adhoc' => $validated['is_adhoc'] ?? false,
            ];

            // Map select values to database fields for custom leave
            if ($leaveType === 'custom') {
                // Start date mapping
                $startHalfSelect = $validated['start_half_select'];
                if ($startHalfSelect === 'full_day') {
                    $leaveData['start_date_type'] = 'full_day';
                    $leaveData['start_half_slot'] = 'morning'; // default
                } else {
                    $leaveData['start_date_type'] = 'half_day';
                    $leaveData['start_half_slot'] = $startHalfSelect === 'first_half' ? 'morning' : 'evening';
                }
                
                // End date mapping
                $endHalfSelect = $validated['end_half_select'];
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

            // Calculate total days for creating multiple leave records
            $totalDays = $startDate->diffInDays($endDate) + 1;
            $leaveRecords = [];

            if ($leaveType === 'full_day') {
                // Full day leave - create one record
                $leaveRecords[] = $leaveData;
            } else {
                // Custom leave type - handle different scenarios
                if ($totalDays === 1) {
                    // Single day custom leave
                    if ($startHalfSelect === 'full_day' && $endHalfSelect === 'full_day') {
                        // Single full day
                        $leaveRecords[] = $leaveData;
                    } elseif ($startHalfSelect !== 'full_day' && $endHalfSelect !== 'full_day') {
                        // Check if different halves selected
                        if ($startHalfSelect !== $endHalfSelect) {
                            // Different halves = full day
                            $leaveRecords[] = $leaveData;
                        } else {
                            // Same half day
                            $leaveRecords[] = $leaveData;
                        }
                    } else {
                        // One half day selected
                        $leaveRecords[] = $leaveData;
                    }
                } else {
                    // Multiple days - create 3 entries as per your requirement
                    
                    // 1. Start date entry (with start date settings)
                    $startRecord = $leaveData;
                    $startRecord['end_date'] = $startDate->format('Y-m-d');
                    if ($startHalfSelect !== 'full_day') {
                        $startRecord['start_date_type'] = 'half_day';
                        $startRecord['end_date_type'] = 'half_day';
                        $startRecord['start_half_slot'] = $startHalfSelect === 'first_half' ? 'morning' : 'evening';
                        $startRecord['end_half_slot'] = $startHalfSelect === 'first_half' ? 'morning' : 'evening';
                        $startRecord['half_day_slot'] = $startRecord['start_half_slot'];
                    }
                    $leaveRecords[] = $startRecord;
                    
                    // 2. Middle days (if any)
                    if ($totalDays > 2) {
                        $middleStartDate = $startDate->copy()->addDay();
                        $middleEndDate = $endDate->copy()->subDay();
                        
                        if ($middleStartDate->lte($middleEndDate)) {
                            $middleRecord = $leaveData;
                            $middleRecord['start_date'] = $middleStartDate->format('Y-m-d');
                            $middleRecord['end_date'] = $middleEndDate->format('Y-m-d');
                            $middleRecord['start_date_type'] = 'full_day';
                            $middleRecord['end_date_type'] = 'full_day';
                            $middleRecord['start_half_slot'] = 'morning';
                            $middleRecord['end_half_slot'] = 'morning';
                            $middleRecord['half_day_slot'] = 'morning';
                            $leaveRecords[] = $middleRecord;
                        }
                    }
                    
                    // 3. End date entry (with end date settings)
                    $endRecord = $leaveData;
                    $endRecord['start_date'] = $endDate->format('Y-m-d');
                    if ($endHalfSelect !== 'full_day') {
                        $endRecord['start_date_type'] = 'half_day';
                        $endRecord['end_date_type'] = 'half_day';
                        $endRecord['start_half_slot'] = $endHalfSelect === 'first_half' ? 'morning' : 'evening';
                        $endRecord['end_half_slot'] = $endHalfSelect === 'first_half' ? 'morning' : 'evening';
                        $endRecord['half_day_slot'] = $endRecord['start_half_slot'];
                    }
                    $leaveRecords[] = $endRecord;
                }
            }

            // Create leave records
            $createdLeaves = [];
            foreach ($leaveRecords as $record) {
                $createdLeaves[] = DoctorLeave::create($record);
            }

            // Disable booking for the doctor
            DoctorProfile::where('user_id', $doctorId)
                ->update(['available_for_booking' => false]);

            DB::commit();

            // Get the first leave for response
            $firstLeave = $createdLeaves[0];
            $firstLeave->load('doctor');

            $message = 'Leave added successfully.';
            if (count($leaveRecords) > 1) {
                $message .= ' (' . count($leaveRecords) . ' leave records created)';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $firstLeave->id,
                    'doctor' => 'Dr. ' . $firstLeave->doctor->first_name . ' ' . $firstLeave->doctor->last_name,
                    'start_date' => $firstLeave->start_date->format('Y-m-d'),
                    'start_date_formatted' => $firstLeave->start_date->format('d M, Y'),
                    'end_date' => $firstLeave->end_date->format('Y-m-d'),
                    'end_date_formatted' => $firstLeave->end_date->format('d M, Y'),
                    'leave_type' => $firstLeave->leave_type,
                    'leave_type_display' => $firstLeave->leave_type === 'full_day' ? 'Full Day' : 'Custom',
                    'reason' => $firstLeave->reason,
                    'status' => ucfirst($firstLeave->status),
                    'is_adhoc' => $firstLeave->is_adhoc,
                    'start_date_type' => $firstLeave->start_date_type ?? 'full_day',
                    'start_half_slot' => $firstLeave->start_half_slot ? ucfirst($firstLeave->start_half_slot) : null,
                    'end_date_type' => $firstLeave->end_date_type ?? 'full_day',
                    'end_half_slot' => $firstLeave->end_half_slot ? ucfirst($firstLeave->end_half_slot) : null,
                    'records_created' => count($leaveRecords),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to add doctor leave: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add leave: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $leave = DoctorLeave::findOrFail($id);

            // Check if there are any conflicting appointments
            $conflictingAppointments = Appointment::where('doctor_id', $leave->doctor_id)
                ->whereBetween('appointment_date', [
                    $leave->start_date,
                    $leave->end_date,
                ])
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->get();

            // Cancel any conflicting appointments
            foreach ($conflictingAppointments as $appointment) {
                $appointment->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Doctor on approved leave',
                ]);
            }

            $leave->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approval_type' => 'frontdesk',
            ]);

            // Disable booking for the doctor
            DoctorProfile::where('user_id', $leave->doctor_id)
                ->update(['available_for_booking' => false]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave approved successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to approve leave: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve leave.',
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $leave = DoctorLeave::findOrFail($id);
            $leave->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave rejected successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to reject leave: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject leave.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $leave = DoctorLeave::findOrFail($id);
            $leave->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave deleted successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to delete leave: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave.',
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