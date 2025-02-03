<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'ciutat', 'capacitat'];
    protected $hidden = ['created_at', 'updated_at'];
}
