<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
