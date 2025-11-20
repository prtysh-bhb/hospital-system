<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('admin.appointments', compact('doctors'));
    }

    // Display the form to add an appointment
    public function addAppointments()
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->with('doctorProfile.specialty')->get();
        return view('admin.add-appointment', compact('patients', 'doctors'));
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

    // Fetch the existing appointment details for a selected patient
    public function getPatientAppointmentDetails($patient_id)
    {
        $appointment = Appointment::where('patient_id', $patient_id)->latest()->first();

        // If the appointment exists, return the data
        if ($appointment) {
            return response()->json([
                'success' => true,
                'data' => $appointment
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No previous appointments found.'
        ]);
    }

    // Store or update an appointment
    public function storeAppointment(Request $request)
    {
        try {
            // Determine whether a patient was selected or new patient details are provided
            $patientId = $request->input('patient_id');

            if ($patientId && $patientId != '') {
                // Validate required fields when patient selected
                $request->validate([
                    'patient_id' => ['required', Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', 'patient');
                    })],
                    'doctor_id' => ['required', Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', 'doctor');
                    })],
                    'appointment_date' => 'required|date|after_or_equal:today',
                    'appointment_time' => 'required|date_format:H:i',
                    'appointment_type' => 'required|in:consultation,follow_up,emergency,check_up',
                    'reason_for_visit' => 'required|string|max:1000',
                ]);
            } else {
                // Validate patient details + appointment when creating new patient
                $request->validate([
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|string|max:20',
                    'date_of_birth' => 'required|date',
                    'gender' => 'required|string',
                    'doctor_id' => ['required', Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', 'doctor');
                    })],
                    'appointment_date' => 'required|date|after_or_equal:today',
                    'appointment_time' => 'required|date_format:H:i',
                    'appointment_type' => 'required|in:consultation,follow_up,emergency,check_up',
                    'reason_for_visit' => 'required|string|max:1000',
                ]);

                // Create patient user
                $user = User::create([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    'gender' => $request->input('gender'),
                    'address' => $request->input('address'),
                    'role' => 'patient',
                    'password' => Hash::make(Str::random(12)),
                ]);

                $patientId = $user->id;
            }

            // Generate appointment number
            $appointmentNumber = 'APT-' . date('Y') . '-' . str_pad(Appointment::count() + 1, 6, '0', STR_PAD_LEFT);

            // Create a new appointment instance
            $appointment = new Appointment();
            $appointment->appointment_number = $appointmentNumber;
            $appointment->patient_id = $patientId;
            $appointment->doctor_id = $request->input('doctor_id');
            $appointment->appointment_date = $request->input('appointment_date');
            $appointment->appointment_time = $request->input('appointment_time');
            $appointment->appointment_type = $request->input('appointment_type');
            $appointment->reason_for_visit = $request->input('reason_for_visit');
            $appointment->status = 'pending';  // Default status
            $appointment->booked_by = auth()->id();
            $appointment->booked_via = 'admin';

            // Save the appointment to the database
            $appointment->save();

            // Return success response
            return response()->json([
                'status' => 200,
                'msg' => 'Appointment saved successfully.'
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return a response
            return response()->json([
                'status' => 400,
                'msg' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getAppointmentsmodal(Request $request){
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->with('doctorProfile.specialty')->get();
        
        $appointment = null;
        if($request->has('appointment_id')){
            $appointment = Appointment::with(['patient', 'doctor.doctorProfile.specialty'])->find($request->appointment_id);
        }
        
        return view('admin.edit-appointment-modal', compact('patients', 'doctors', 'appointment'));
    }

    // Update appointment
    public function updateAppointment(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'appointment_id' => [
                    'required',
                    'exists:appointments,id',
                ],
                'patient_id' => [
                    'required',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', 'patient');
                    }),
                ],
                'doctor_id' => [
                    'required',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', 'doctor');
                    }),
                ],
                'appointment_date' => [
                    'required',
                    'date',
                ],
                'appointment_time' => [
                    'required',
                    'date_format:H:i',
                ],
                'appointment_type' => [
                    'required',
                    'in:consultation,follow_up,emergency,check_up',
                ],
                'reason_for_visit' => [
                    'required',
                    'string',
                    'max:1000',
                ],
                'status' => [
                    'required',
                    'in:pending,confirmed,checked_in,in_progress,completed,cancelled,no_show',
                ],
            ], [
                'patient_id.required' => 'Please select a valid patient.',
                'patient_id.exists' => 'The selected patient does not exist or is not valid.',
                'doctor_id.required' => 'Please select a valid doctor.',
                'doctor_id.exists' => 'The selected doctor does not exist or is not valid.',
                'appointment_date.required' => 'Appointment date is required.',
                'appointment_date.date' => 'Please provide a valid date for the appointment.',
                'appointment_time.required' => 'Appointment time is required.',
                'appointment_time.date_format' => 'Please provide a valid time in the format HH:MM.',
                'appointment_type.required' => 'Please select the appointment type.',
                'appointment_type.in' => 'Please select a valid appointment type.',
                'reason_for_visit.required' => 'Reason for visit is required.',
                'reason_for_visit.max' => 'Reason for visit cannot be longer than 1000 characters.',
                'status.required' => 'Status is required.',
                'status.in' => 'Please select a valid status.',
            ]);

            // Find and update the appointment
            $appointment = Appointment::find($request->appointment_id);
            
            if (!$appointment) {
                return response()->json([
                    'status' => 404,
                    'msg' => 'Appointment not found.'
                ]);
            }

            $appointment->patient_id = $request->input('patient_id');
            $appointment->doctor_id = $request->input('doctor_id');
            $appointment->appointment_date = $request->input('appointment_date');
            $appointment->appointment_time = $request->input('appointment_time');
            $appointment->appointment_type = $request->input('appointment_type');
            $appointment->reason_for_visit = $request->input('reason_for_visit');
            $appointment->status = $request->input('status');
            $appointment->notes = $request->input('notes', $appointment->notes);
            
            $appointment->save();

            return response()->json([
                'status' => 200,
                'msg' => 'Appointment updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'msg' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ]);
        }
    }

    // Delete appointment
    public function deleteAppointment(Request $request)
    {
        try {
            $validated = $request->validate([
                'appointment_id' => 'required|exists:appointments,id',
             ]);

            $appointment = Appointment::find($request->appointment_id);
            
            if (!$appointment) {
                return response()->json([
                    'status' => 404,
                    'msg' => 'Appointment not found.'
                ]);
            }

            $appointment->delete();

            return response()->json([
                'status' => 200,
                'msg' => 'Appointment deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'msg' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ]);
        }
    }

    // View appointment details
    public function viewAppointment($id)
    {
        try {
            $appointment = Appointment::with(['patient', 'doctor.doctorProfile.specialty'])->find($id);
            
            if (!$appointment) {
                return response()->json([
                    'status' => 404,
                    'msg' => 'Appointment not found.'
                ]);
            }

            return response()->json([
                'status' => 200,
                'data' => $appointment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'msg' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ]);
        }
    }

}
