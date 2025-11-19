<?php

namespace App\Http\Controllers\public;

use App\Models\Specialty;
use App\Models\User;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\public\BookAppointmentService;


class BookAppointmentController extends Controller
{
    protected $service;
    public function __construct(BookAppointmentService $service)
    {
        $this->service = $service;
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
            if (!session()->has('doctor_id')) {
                return redirect()->route('booking', ['step' => 1]);
            }

            $doctor_id = session('doctor_id');
            $doctor = User::with('doctorProfile.specialty')->find($doctor_id);

            $schedules = DoctorSchedule::where('doctor_id', $doctor_id)->get();
            $availableDays = $schedules->pluck('day_of_week')->toArray();

            $monthStart = now()->startOfMonth();
            $monthEnd = now()->endOfMonth();

            $calendar = [];
            $cursor = $monthStart->copy();
            while ($cursor <= $monthEnd) {
                $calendar[] = [
                    'date' => $cursor->format('Y-m-d'),
                    'day' => $cursor->day,
                    'weekday' => $cursor->dayOfWeek,
                    'is_available' => in_array($cursor->dayOfWeek, $availableDays),
                    'is_today' => $cursor->isToday()
                ];
                $cursor->addDay();
            }

            $selectedDate = $request->get('date', now()->format('Y-m-d'));
            $selectedWeekday = Carbon::parse($selectedDate)->dayOfWeek;

            $todaySchedule = DoctorSchedule::where('doctor_id', $doctor_id)
                ->where('day_of_week', $selectedWeekday)
                ->first();

            $slots = [];
            if ($todaySchedule) {
                $start = Carbon::parse($todaySchedule->start_time);
                $end = Carbon::parse($todaySchedule->end_time);

                while ($start < $end) {
                    $slots[] = $start->format('h:i A');
                    $start->addMinutes($todaySchedule->slot_duration);
                }
            }

            // If user selected a slot
            $selectedSlot = $request->get('slot');

            return view('public.booking', compact(
                'step',
                'doctor',
                'calendar',
                'slots',
                'selectedDate',
                'specialties',
                'doctors',
                'specialty_id',
                'selectedSlot'
            ));
        }

        // -----------------------------
        // STEP-3 — Patient Details
        // -----------------------------
        if ($step == 3) {
            if (!session()->has('doctor_id') || !session()->has('selectedDate') || !session()->has('selectedSlot')) {
                return redirect()->route('booking', ['step' => 2]);
            }

            $doctor_id = session('doctor_id');
            $doctor = User::with('doctorProfile.specialty')->find($doctor_id);
            $selectedDate = session('selectedDate');
            $selectedSlot = session('selectedSlot');

            return view('public.booking', compact(
                'step',
                'doctor',
                'selectedDate',
                'selectedSlot',
                'specialties',
                'doctors',
                'specialty_id'
            ));
        }

        return view('public.booking', compact('step', 'specialties', 'doctors', 'specialty_id'));
    }



    public function getSlots(Request $request)
    {
        $doctor_id = session('doctor_id');
        $date = $request->date;

        if (!$doctor_id || !$date) {
            return response()->json(['slots' => []]);
        }

        $weekday = Carbon::parse($date)->dayOfWeek;

        $schedule = DoctorSchedule::where('doctor_id', $doctor_id)
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
            'slots' => $slots
        ]);
    }




    public function store(Request $request)
    {
        if ($request->step == 1) {
            session(['doctor_id' => $request->doctor_id]);
            return redirect()->route('booking', ['step' => 2]);
        }

        if ($request->step == 2) {
            session([
                'date' => $request->date,
                'time' => $request->time
            ]);
            return redirect()->route('booking', ['step' => 3]);
        }
    }

}
