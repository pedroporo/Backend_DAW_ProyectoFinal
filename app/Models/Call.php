<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Call extends Model
{

    protected $fillable = [
        'timestamp',
        'patient_id',
        'user_id',
        'description',
    ];

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getFormattedTimestampAttribute()
    {
        return $this->timestamp->format('d-m-Y H:i');
    }
}
