<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncomingCall extends Call {
    use HasFactory;

    protected $fillable = [
        'timestamp',
        'patient_id',
        'user_id',
        'type',
        'description',
    ];

    const TYPES = [
        'social_emergency',
        'medical_emergency',
        'loneliness_crisis',
        'unanswered_alarm',
        'absence_notification',
        'data_update',
        'accidental',
        'info_request',
        'complaint',
        'social_call',
        'medical_appointment',
        'other',
    ];

    public function getTypeAttribute($value)
    {
        return ucfirst(str_replace('_', ' ', $value));
    }


}

?>