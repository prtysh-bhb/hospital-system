<?php

namespace App\Services\public;

class BookAppointmentService
{
    public function storeStepData($step, $request)
    {
        switch ($step) {

            case 1:
                session([
                    'booking.step1' => [
                        'patient_name' => $request->patient_name,
                        'patient_email' => $request->patient_email,
                    ]
                ]);
                break;

            case 2:
                session([
                    'booking.step2' => [
                        'doctor_id' => $request->doctor_id,
                        'department' => $request->department,
                    ]
                ]);
                break;

            case 3:
                session([
                    'booking.step3' => [
                        'date' => $request->date,
                        'time' => $request->time,
                    ]
                ]);
                break;

            case 4:
                session([
                    'booking.step4' => [
                        'notes' => $request->notes,
                    ]
                ]);
                break;
        }
    }
}
