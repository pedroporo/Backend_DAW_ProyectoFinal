<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    /**
     * @OA\Schema(
     *     schema="Contact",
     *     description="Esquema del model Contact",
     *     @OA\Property(property="first_name", type="string", maxLength=255, description="Nombre del contacto", example="Pedro"),
     *     @OA\Property(property="last_name", type="string", maxLength=255, description="Apellidos del contacto", example="Guill Ferri"),
     *     @OA\Property(property="phone", type="string", maxLength=255, description="Numero telefonico del contacto"),
     *     @OA\Property(property="patient_id", type="integer", description="Id del paciente"),
     *     @OA\Property(property="relationship", type="string", maxLength=255, description="Relacion personal con el paciente")
     * )
     */
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'patient_id',
        'relationship'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

}
