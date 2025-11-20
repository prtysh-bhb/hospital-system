<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Services\doctore\CalenderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected CalenderService $calendarService;

    public function __construct(CalenderService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Display the calendar page
     */
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        return view('doctor.calendar', compact('currentYear', 'currentMonth'));
    }

    /**
     * Get calendar data for a specific month (AJAX)
     */
    public function getCalendarData(Request $request)
    {
        try {
            $yearMonth = $request->input('month', Carbon::now()->format('Y-m'));
            [$year, $month] = explode('-', $yearMonth);

            \Log::info('Loading calendar for doctor', [
                'doctor_id' => auth()->id(),
                'year' => $year,
                'month' => $month,
            ]);

            $calendarData = $this->calendarService->getCalendarData((int) $year, (int) $month);

            return response()->json([
                'success' => true,
                'data' => $calendarData,
            ]);
        } catch (\Exception $e) {
            \Log::error('Calendar data error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load calendar data',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get weekly schedule (AJAX)
     */
    public function getWeeklySchedule()
    {
        $schedule = $this->calendarService->getWeeklySchedule();

        return response()->json([
            'success' => true,
            'schedule' => $schedule,
        ]);
    }

    /**
     * Get appointments for a specific date (AJAX)
     */
    public function getDateAppointments(Request $request)
    {
        $date = $request->input('date');

        if (! $date) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required',
            ], 400);
        }

        $appointments = $this->calendarService->getDateAppointments($date);

        return response()->json([
            'success' => true,
            'date' => Carbon::parse($date)->format('F d, Y'),
            'appointments' => $appointments,
        ]);
    }
}
