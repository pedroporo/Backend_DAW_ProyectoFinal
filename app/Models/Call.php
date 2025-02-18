<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @OA\Schema(
 *     schema="Call",
 *     description="Modelo de llamada (entrada o salida), representando una llamada entre un paciente y un operador",
 *     @OA\Property(property="timestamp", type="string", format="date-time", description="Fecha y hora de la llamada", example="2025-02-18T15:30:00"),
 *     @OA\Property(property="patient_id", type="integer", description="ID del paciente relacionado", example=37),
 *     @OA\Property(property="user_id", type="integer", description="ID del usuario que realizó la llamada", example=42),
 *     @OA\Property(property="description", type="string", maxLength=1000, description="Descripción de la llamada", example="Descripción de la llamada realizada al paciente"),
 *     @OA\Property(property="formatted_timestamp", type="string", description="Fecha y hora de la llamada formateada", example="18-02-2025 15:30"),
 *     @OA\Property(property="patient", ref="#/components/schemas/Patient"),
 * )
 */
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
