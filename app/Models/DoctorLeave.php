<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorLeave extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'doctor_leaves';

    protected $fillable = [
        'doctor_id',
        'approval_type',
        'approved_by',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'leave_type',
        'half_day_slot',
        'reason',
        'status',
        'is_adhoc',
        'start_date_type',
        'start_half_slot',
        'end_date_type',
        'end_half_slot',
    ];

    protected $casts = [
        'is_adhoc' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
