<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="OutgoingCall",
 *     description="Modelo de llamada saliente, heredado del modelo Call, con información adicional sobre si es planificada y relacionada con una alarma",
 *     @OA\Property(property="timestamp", type="string", format="date-time", description="Fecha y hora de la llamada", example="2025-02-18T15:30:00"),
 *     @OA\Property(property="patient_id", type="integer", description="ID del paciente relacionado", example=37),
 *     @OA\Property(property="user_id", type="integer", description="ID del operador que realizó la llamada", example=42),
 *     @OA\Property(property="is_planned", type="boolean", description="Indica si la llamada fue planificada", example=true),
 *     @OA\Property(property="description", type="string", maxLength=1000, description="Descripción de la llamada", example="Descripción de la llamada saliente"),
 *     @OA\Property(property="alarm_id", type="integer", description="ID de la alarma asociada a la llamada", example=5),
 *     @OA\Property(property="formatted_timestamp", type="string", description="Fecha y hora de la llamada formateada", example="18-02-2025 15:30")
 * )
 */
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