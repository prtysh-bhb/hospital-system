<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifiyUserEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
       array $data
    ) {
        // ✅ ONE LOG ONLY
        // Log::info('Appointments cancelled by doctor due to leave', [
            
        // ]);
       
    }
}
