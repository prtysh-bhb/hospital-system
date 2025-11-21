<?php

namespace App\Http\Controllers\doctor;

use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\doctore\DoctorAppointmentServices;

class DoctorAppointmentController extends Controller
{
    public function index()
    { 
        
        

        return view('doctor.appointments');
    }
    
    /**
     * AJAX endpoint returning doctor's appointments (JSON)
     */
    public function doctorAppointmentData(Request $request, DoctorAppointmentServices $svc)
    {
        $doctorId = Auth::id();
        $filters = [];
        // allow optional date (Y-m-d), status, search
        if ($request->has('date') && !empty($request->get('date'))) {
            // validate date format loosely
            $filters['date'] = Carbon::parse($request->get('date'))->toDateString();
        }
        if ($request->has('status')) {
            $filters['status'] = $request->get('status');
        }
        if ($request->has('search')) {
            $filters['search'] = $request->get('search');
        }

        $perPage = 5;

        $paginated = $svc->getTodayAppointments($doctorId, $filters)
            ->paginate($perPage);

        $paginated->getCollection()->transform(function ($a) {
                $patient = $a->patient;
                $profile = optional($patient)->patientProfile;
                // compute age if date_of_birth exists
                $age = null;
                if (!empty(optional($patient)->date_of_birth)) {
                    try {
                        $age = Carbon::parse($patient->date_of_birth)->age;
                    } catch (\Exception $e) {
                        $age = null;
                    }
                }

                return [
                    'id' => $a->id,
                    'appointment_number' => $a->appointment_number,
                    'patient_id' => optional($patient)->id,
                    'patient_name' => trim(optional($patient)->first_name . ' ' . optional($patient)->last_name),
                    'patient_age' => $age,
                    'patient_gender' => optional($patient)->gender,
                    'patient_phone' => optional($patient)->phone,
                    'patient_allergies' => optional($profile)->allergies,
                    'reason' => $a->reason_for_visit,
                    'date' => optional($a->appointment_date) ? optional($a->appointment_date)->format('d-m-Y') : '',
                    'time' => optional($a->appointment_time) ? Carbon::parse($a->appointment_time)->format('h:i A') : '',
                    'status' => $a->status,
                    'details_url' => route('doctor.appointment-details', ['id' => $a->id]),
                ];
        });

        return response()->json($paginated);
    }
    
    /**
     * Mark an appointment as completed (AJAX).
     */
    public function completeAppointment(Request $request, $id)
    {
        try {
            $doctorId = Auth::id();

            // Fetch appointment belonging to this doctor
            $appointment = Appointment::where('id', $id)
                ->where('doctor_id', $doctorId)
                ->first();

            if (!$appointment) {
                return response()->json([
                    'status' => 404,
                    'msg' => 'Appointment not found or unauthorized'
                ], 404);
            }

            // Mark appointment as completed
            $appointment->status = 'completed';
            $appointment->save();

            return response()->json([
                'status' => 200,
                'msg' => 'Appointment marked as completed',
                'id' => $appointment->id
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'status' => 422,
                'msg' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 400,
                'msg' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ], 400);

        }
    }

    public function doctorAppointmentDetails($id)
    {
        return view('doctor.appointment-details', compact('id'));
    }
}
