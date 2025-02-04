<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'last_name',
        'birth_date',
        'address',
        'city',
        'postal_code',
        'dni',
        'health_card_number',
        'phone',
        'email',
        'zone_id',
        'user_id',
        'personal_situation',
        'health_situation',
        'housing_situation',
        'personal_autonomy',
        'economic_situation'
    ];
    protected $hidden = ['created_at', 'updated_at'];
}
