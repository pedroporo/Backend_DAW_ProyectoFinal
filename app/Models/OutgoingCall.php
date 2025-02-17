<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class OutgoingCall extends Call {
    
    use HasFactory;
    protected $fillable = [
        'timestamp', 'patient_id', 'user_id', 'is_planned', 'description', 'alarm_id'
    ];

    public function alert(){
        return $this->belongsTo(Alert::class);
    }

}

?>