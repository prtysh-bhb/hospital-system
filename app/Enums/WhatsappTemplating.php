<?php

namespace App\Enums;

enum WhatsappTemplating:string
{
    case CANCEL_APPOINTMENT = 'appointment_reschedule_1';
    case RESCHEDULE_APPOINTMENT = 'appointment_reschedule';
}
