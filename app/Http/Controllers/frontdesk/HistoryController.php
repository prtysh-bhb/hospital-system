<?php

namespace App\Http\Controllers\frontdesk;

use App\Http\Controllers\Controller;
use App\services\frontdesk\HistoryService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $historyService;

    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Display appointment history page
     */
    public function index(Request $request)
    {
        $filters = [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'status' => $request->status,
            'search' => $request->search,
        ];

        // Check if it's an AJAX request or expects JSON
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            try {
                $appointments = $this->historyService->getAppointmentsHistory($filters);
                $statistics = $this->historyService->getStatistics($filters);

                return response()->json([
                    'success' => true,
                    'appointments' => $appointments->items(),
                    'statistics' => $statistics,
                    'pagination' => [
                        'current_page' => $appointments->currentPage(),
                        'last_page' => $appointments->lastPage(),
                        'per_page' => $appointments->perPage(),
                        'total' => $appointments->total(),
                        'from' => $appointments->firstItem(),
                        'to' => $appointments->lastItem(),
                    ],
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return view('frontdesk.history');
    }

    /**
     * Get appointment details
     */
    public function show($id)
    {
        try {
            $appointment = $this->historyService->getAppointmentDetails($id);

            return response()->json([
                'success' => true,
                'appointment' => [
                    'id' => $appointment->id,
                    'appointment_number' => $appointment->appointment_number,
                    'patient_name' => $appointment->patient->full_name,
                    'patient_email' => $appointment->patient->email,
                    'patient_phone' => $appointment->patient->phone,
                    'doctor_name' => $appointment->doctor->full_name,
                    'specialization' => $appointment->doctor->doctorProfile->specialization ?? 'N/A',
                    'appointment_date' => $appointment->appointment_date->format('M d, Y'),
                    'appointment_time' => \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A'),
                    'status' => $appointment->status,
                    'appointment_type' => $appointment->appointment_type,
                    'reason_for_visit' => $appointment->reason_for_visit,
                    'symptoms' => $appointment->symptoms,
                    'notes' => $appointment->notes,
                    'cancellation_reason' => $appointment->cancellation_reason,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }
    }
}
