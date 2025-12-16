<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AppointmentCancelEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        int $doctorId,
        ?string $reason = null
    ) {
        // ✅ ONE LOG ONLY
        Log::info('Appointments cancelled by doctor due to leave', [
            'doctor_id' => $doctorId,
            'reason' => $reason,
            'source' => 'doctor_leave',
        ]);
    }
}
