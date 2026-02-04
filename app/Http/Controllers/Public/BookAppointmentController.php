<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\Setting;
use App\Models\Specialty;
use App\Models\User;
use App\Services\AppointmentSlotService;
use App\Services\Public\BookAppointmentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookAppointmentController extends Controller
{
    protected $service;

    protected $slotService;

    public function __construct(BookAppointmentService $service, AppointmentSlotService $slotService)
    {
        $this->service = $service;
        $this->slotService = $slotService;
    }

    public function index(Request $request)
    {
        $step = $request->get('step', 1);

        // Step-1
        $specialties = Specialty::all();
        $specialty_id = $request->get('specialty_id');

        $doctors = User::where('role', 'doctor')
            ->when($specialty_id, function ($query) use ($specialty_id) {
                $query->whereHas('doctorProfile', function ($q) use ($specialty_id) {
                    $q->where('specialty_id', $specialty_id);
                });
            })
            ->with(['doctorProfile.specialty'])
            ->get();

        // -----------------------------
        // STEP-2 — Calendar + Slots
        // -----------------------------
        if ($step == 2) {

            if (! session()->has('doctor_id')) {
                return redirect()->route('booking', ['step' => 1]);
            }
            $publicAdvanceBookingDays = Setting::getValue('appointment_booking_days', 30);
            $doctor_id = session('doctor_id');
            $doctor = User::with('doctorProfile.specialty')->find($doctor_id);

            $schedules = DoctorSchedule::where('doctor_id', $doctor_id)->get();
            $availableDays = $schedules->pluck('day_of_week')->toArray();

            // Build calendar
            $startDate = now();
            $endDate = now()->addDays(30);

            $calendar = [];

            // Add padding for the first week
            $startDayOfWeek = $startDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
            for ($i = 0; $i < $startDayOfWeek; $i++) {
                $calendar[] = null;
            }

            $cursor = $startDate->copy();
            while ($cursor <= $endDate) {
                $calendar[] = [
                    'date' => $cursor->format('Y-m-d'),
                    'day' => $cursor->day,
                    'weekday' => $cursor->dayOfWeek,
                    'is_available' => in_array($cursor->dayOfWeek, $availableDays),
                    'is_today' => $cursor->isToday(),
                ];
                $cursor->addDay();
            }

            $selectedDate = $request->get('date', now()->format('Y-m-d'));

            // Get available slots using the service
            $slotResult = $this->slotService->getAvailableSlots($doctor_id, $selectedDate);
            $slots = $slotResult['success'] ? $slotResult['slots'] : [];

            // Send all variables to view
            return view('public.booking', compact(
                'step',
                'doctor',
                'calendar',
                'slots',
                'selectedDate',
                'specialties',
                'doctors',
                'specialty_id',
                'publicAdvanceBookingDays'
            ));
        }
        // -----------------------------
        // STEP-3 — Patient Details
        // -----------------------------
        if ($step == 3) {
            if (! session()->has('doctor_id') || ! session()->has('selectedDate') || ! session()->has('selectedSlot')) {
                return redirect()->route('booking', ['step' => 2]);
            }

            $doctor_id = session('doctor_id');
            $doctor = User::with('doctorProfile.specialty')->find($doctor_id);
            $selectedDate = session('selectedDate');
            $selectedSlot = session('selectedSlot');

            // Get form field visibility settings (centralized method)
            $formSettings = BookAppointmentService::getFormSettings();

            return view('public.booking', compact(
                'step',
                'doctor',
                'selectedDate',
                'selectedSlot',
                'specialties',
                'doctors',
                'specialty_id',
                'formSettings'
            ));
        }

        // -----------------------------
        // STEP-4 — Confirmation
        // -----------------------------
        if ($step == 4) {
            if (! session()->has('appointment_id')) {
                return redirect()->route('booking', ['step' => 1]);
            }

            $appointment = $this->service->getAppointmentDetails(session('appointment_id'));

            return view('public.booking', compact(
                'step',
                'appointment',
                'specialties',
                'doctors',
                'specialty_id'
            ));
        }

        return view('public.booking', compact('step', 'specialties', 'doctors', 'specialty_id'));
    }

    public function downloadPDFAppointment(Request $request)
    {
        // Appointment ID get from request query
        $appointmentId = $request->query('appointment_id');

        $appointment = $this->service->getAppointmentDetails($appointmentId);

        if (! $appointment) {
            return response()->json(['msg' => 'Appointment not found.'], 404);
        }

        // Generate PDF
        $pdf = PDF::loadView('public.appointment_confirmed_pdf', compact('appointment'));

        // Return PDF as download
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf');
    }

    public function getSlots(Request $request)
    {
        $doctor_id = session('doctor_id');
        $date = $request->date;

        $result = $this->slotService->getAvailableSlots($doctor_id, $date);

        return response()->json([
            'slots' => $result['slots'],
        ]);
    }

    public function store(Request $request)
    {
        // Step 1: Select doctor
        if ($request->step == 1) {
            session(['doctor_id' => $request->doctor_id]);

            return redirect()->route('booking', ['step' => 2]);
        }

        // Step 2: Select date and slot
        if ($request->step == 2) {
            // Only date provided (calendar click)
            if ($request->has('date') && ! $request->has('slot')) {
                return redirect()->route('booking', ['step' => 2, 'date' => $request->date]);
            }

            // Date and slot selected, proceed to step 3
            if ($request->has('date') && $request->has('slot')) {
                session([
                    'selectedDate' => $request->date,
                    'selectedSlot' => $request->slot,
                ]);

                if ($request->ajax()) {
                    return response()->json(['success' => true]);
                }

                return redirect()->route('booking', ['step' => 3]);
            }

            // Neither date nor slot provided, reload step 2
            return redirect()->route('booking', ['step' => 2]);
        }

        // Step 3: Patient details and appointment creation
        if ($request->step == 3) {
            // Get field visibility settings
            $settings = app('settings');

            // Build validation rules based on which fields are visible
            $rules = [
                'first_name' => 'required|string|min:2|max:255|regex:/^[a-zA-Z\s]+$/',
            ];

            // Last name - required only if visible in settings
            if ($settings->get('show_last_name', false)) {
                $rules['last_name'] = 'required|string|min:2|max:255|regex:/^[a-zA-Z\s]+$/';
            } else {
                $rules['last_name'] = 'nullable|string|min:2|max:255|regex:/^[a-zA-Z\s]+$/';
            }

            // Email - required only if visible in settings
            if ($settings->get('show_email', false)) {
                $rules['email'] = 'required|email|max:255|unique:users,email';
            } else {
                $rules['email'] = 'nullable|email|max:255';
            }

            // Phone - required only if visible in settings
            if ($settings->get('show_phone', false)) {
                $rules['phone'] = ['required', 'regex:/^[0-9]{10,15}$/', 'not_regex:/^0+$/'];
            } else {
                $rules['phone'] = ['nullable', 'regex:/^[0-9]{10,15}$/'];
            }

            // Date of Birth - required only if visible in settings
            if ($settings->get('show_date_of_birth', false)) {
                $rules['date_of_birth'] = 'required|date|before_or_equal:today';
            } else {
                $rules['date_of_birth'] = 'nullable|date|before_or_equal:today';
            }

            // Gender - required only if visible in settings
            if ($settings->get('show_gender', false)) {
                $rules['gender'] = 'required|in:male,female,other';
            } else {
                $rules['gender'] = 'nullable|in:male,female,other';
            }

            // Address - optional if visible
            if ($settings->get('show_address', false)) {
                $rules['address'] = 'nullable|string|min:10|max:500';
            }

            // Reason for Visit - required only if visible in settings
            if ($settings->get('show_reason_for_visit', false)) {
                $rules['reason_for_visit'] = 'required|string|min:10|max:1000';
            } else {
                $rules['reason_for_visit'] = 'nullable|string|min:10|max:1000';
            }

            // Optional fields - only validate if visible
            if ($settings->get('show_allergies', false)) {
                $rules['allergies'] = 'nullable|string|max:500';
            }

            if ($settings->get('show_emergency_contact_name', false)) {
                $rules['emergency_contact_name'] = 'nullable|string|min:2|max:255';
                $rules['emergency_contact_phone'] = 'nullable|regex:/^[0-9]{10,15}$/';
            }

            if ($settings->get('show_blood_group', false)) {
                $rules['blood_group'] = 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-';
            }

            if ($settings->get('show_medical_history', false)) {
                $rules['medical_history'] = 'nullable|string|max:1000';
            }

            if ($settings->get('show_current_medications', false)) {
                $rules['current_medications'] = 'nullable|string|max:1000';
            }

            if ($settings->get('show_insurance_details', false)) {
                $rules['insurance_provider'] = 'nullable|string|max:255';
                $rules['insurance_number'] = 'nullable|string|max:255';
            }

            // Validate patient details
            $validated = $request->validate($rules);

            \Log::info('Step 3 validation passed', ['data' => $validated]);

            // Prepare appointment data
            $appointmentData = array_merge($validated, [
                'doctor_id' => session('doctor_id'),
                'appointment_date' => session('selectedDate'),
                'appointment_time' => session('selectedSlot'),
                'booked_via' => 'online', // default
            ]);

            \Log::info('Calling createAppointment service', ['appointmentData' => $appointmentData]);

            // Call service
            $result = $this->service->createAppointment($appointmentData);

            \Log::info('Service result', ['result' => $result]);

            // If appointment created successfully
            if ($result['success']) {
                session(['appointment_id' => $result['appointment_id']]);
                \Log::info('Redirecting to step 4', ['appointment_id' => $result['appointment_id']]);

                return redirect()->route('booking', ['step' => 4]);
            }

            // Return detailed error message from service
            $errorMessage = $result['message'] ?? 'Failed to create appointment. Please try again.';
            \Log::error('Failed to create appointment', ['error' => $errorMessage, 'result' => $result]);

            return back()->withErrors(['error' => $errorMessage]);
        }

        // Default: redirect to step 1
        return redirect()->route('booking', ['step' => 1]);
    }
}
