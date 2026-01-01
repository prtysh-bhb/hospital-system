<?php

namespace App\Models;

use App\Enums\WhatsappTemplating;
use Illuminate\Database\Eloquent\Model;

class Whatsapp extends Model
{
    protected $casts = [
        'template' => WhatsappTemplating::class,
    ];
}
