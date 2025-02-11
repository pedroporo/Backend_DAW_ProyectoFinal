<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    /**
     * @OA\Schema(
     *     schema="Patient",
     *     description="Esquema del model Patient",
     *     @OA\Property(property="name", type="string", maxLength=255, description="Nombre del paciente", example="Pedro"),
     *     @OA\Property(property="last_name", type="string", maxLength=255, description="Apellidos del paciente", example="Guill Ferri"),
     *     @OA\Property(
     *         property="birth_date",
     *         type="string",
     *         format="date",
     *         description="Data de naixement del paciente.",
     *         example="15-05-2002"
     *     ),
     *     @OA\Property(property="address", type="string", maxLength=255, description="Direccion del paciente"),
     *     @OA\Property(property="city", type="string", maxLength=255, description="Ciudad del paciente"),
     *     @OA\Property(property="postal_code", type="integer", description="Codigo postal del paciente"),
     *     @OA\Property(property="dni", type="string", maxLength=255, description="Dni del paciente"),
     *     @OA\Property(property="health_card_number", type="integer", description="Numero social"),
     *     @OA\Property(property="phone", type="string", maxLength=255, description="Numero telefonico del paciente"),
     *     @OA\Property(property="email", type="string", maxLength=255, description="Correo electronico del paciente"),
     *     @OA\Property(property="zone_id", type="integer", description="Id de la zona en la que vive"),
     *     @OA\Property(property="personal_situation", type="string", maxLength=255, description="Situacion personal del paciente"),
     *     @OA\Property(property="health_situation", type="string", maxLength=255, description="Situacion de vida del paciente"),
     *     @OA\Property(property="housing_situation", type="string", maxLength=255, description="Situacion de vivienda del paciente"),
     *     @OA\Property(property="personal_autonomy", type="string", maxLength=255, description="Autonomia del paciente"),
     *     @OA\Property(property="economic_situation", type="string", maxLength=255, description="Situacion economica del paciente"),
     *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de creació del registre"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data d'actualització del registre")
     * )
     */
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
        'personal_situation',
        'health_situation',
        'housing_situation',
        'personal_autonomy',
        'economic_situation'
    ];

    protected $hidden = ['created_at', 'updated_at'];
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function contactPersons(){
        return $this->hasMany(Contact::class);
    }

    public function calls(){
        return $this->hasMany(Call::class);
    }

    public function alerts(){
        return $this->hasMany(Alert::class);
    }

}
