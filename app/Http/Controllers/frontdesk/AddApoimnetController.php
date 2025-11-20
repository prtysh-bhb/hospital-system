<?php

namespace App\Http\Controllers\frontdesk;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\User;
use App\Services\public\BookAppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AddApoimnetController extends Controller
{
    protected $bookingService;

    public function __construct(BookAppointmentService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        return view('frontdesk.add-appointment');
    }

    public function searchPatient(Request $request)
    {
        $search = $request->get('search');

        $patients = User::where('role', 'patient')
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->with('patientProfile')
            ->limit(10)
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->full_name,
                    'email' => $patient->email,
                    'phone' => $patient->phone,
                    'date_of_birth' => $patient->date_of_birth,
                    'gender' => $patient->gender,
                    'address' => $patient->address,
                ];
            });

        return response()->json([
            'success' => true,
            'patients' => $patients,
        ]);
    }

    public function getDoctors(Request $request)
    {
        $doctors = User::where('role', 'doctor')
            ->where('status', 'active')
            ->with(['doctorProfile.specialty'])
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->full_name,
                    'specialty' => $doctor->doctorProfile->specialty->name ?? 'N/A',
                    'fee' => $doctor->doctorProfile->consultation_fee ?? 0,
                ];
            });

        return response()->json([
            'success' => true,
            'doctors' => $doctors,
        ]);
    }

    public function getAvailableSlots(Request $request)
    {
        $doctorId = $request->get('doctor_id');
        $date = $request->get('date');

        if (! $doctorId || ! $date) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor and date are required',
            ]);
        }

        $weekday = Carbon::parse($date)->dayOfWeek;

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $weekday)
            ->first();

        $slots = [];
        if ($schedule) {
            $start = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);

            while ($start < $end) {
                $slots[] = $start->format('h:i A');
                $start->addMinutes($schedule->slot_duration);
            }
        }

        return response()->json([
            'success' => true,
            'slots' => $slots,
        ]);
    }

    public function store(Request $request)
    {
     
        $validated = $request->validate([
            'patient_id' => 'nullable|exists:users,id',
            'first_name' => 'required_without:patient_id|string|min:2|max:255',
            'last_name' => 'required_without:patient_id|string|min:2|max:255',
            'email' => 'required_without:patient_id|email|max:255',
            'phone' => 'required_without:patient_id|string|min:10|max:15',
            'date_of_birth' => 'required_without:patient_id|date|before:today',
            'gender' => 'required_without:patient_id|in:male,female,other',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|string',
            'appointment_type' => 'required|in:consultation,follow_up,emergency,check_up',
            'reason_for_visit' => 'required|string|min:10',
            'notes' => 'nullable|string',
        ]);

        $appointmentData = [
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'appointment_type' => $validated['appointment_type'],
            'reason_for_visit' => $validated['reason_for_visit'],
            'notes' => $validated['notes'] ?? null,
            'booked_via' => 'frontdesk',
        ];
       
        // If patient_id exists, use existing patient
        if (! empty($validated['patient_id'])) {
            $patient = User::find($validated['patient_id']);
            $appointmentData = array_merge($appointmentData, [
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'email' => $patient->email,
                'phone' => $patient->phone,
                'date_of_birth' => $patient->date_of_birth,
                'gender' => $patient->gender,
                'address' => $patient->address,
            ]);
        } else {
            // Create new patient
            $appointmentData = array_merge($appointmentData, [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'address' => $validated['address'] ?? null,
            ]);
        }
        $result = $this->bookingService->createAppointment($appointmentData); 

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully',
                'appointment_id' => $result['appointment_id'],
                'appointment_number' => $result['appointment_number'],
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Failed to create appointment',
        ], 500);
    }
}
