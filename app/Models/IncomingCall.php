<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * @OA\Schema(
 *     schema="IncomingCall",
 *     description="Modelo de llamada entrante, heredado del modelo Call, con información adicional sobre el tipo de llamada",
 *     @OA\Property(property="timestamp", type="string", format="date-time", description="Fecha y hora de la llamada", example="2025-02-18T15:30:00"),
 *     @OA\Property(property="patient_id", type="integer", description="ID del paciente relacionado", example=37),
 *     @OA\Property(property="user_id", type="integer", description="ID del operador que realizó la llamada", example=42),
 *     @OA\Property(
 *         property="type",
 *         type="enum",
 *         description="Tipo de llamada (social_emergency, medical_emergency, etc.)",
 *         enum={
 *             "social_emergency",
 *             "medical_emergency",
 *             "loneliness_crisis",
 *             "unanswered_alarm",
 *             "absence_notification",
 *             "data_update",
 *             "accidental",
 *             "info_request",
 *             "complaint",
 *             "social_call",
 *             "medical_appointment",
 *             "other"
 *         },
 *         example="social_emergency"
 *     ),
 *     @OA\Property(property="description", type="string", maxLength=1000, description="Descripción de la llamada", example="Descripción de la llamada entrante"),
 *     @OA\Property(property="formatted_timestamp", type="string", description="Fecha y hora de la llamada formateada", example="18-02-2025 15:30")
 * )
 */
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