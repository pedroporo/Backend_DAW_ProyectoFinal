<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Alert extends Model
{
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
