<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="PatientResource",
     *     description="Esquema del recurs Patient",
     *     @OA\Property(property="name", type="string", maxLength=255, description="Nombre del paciente", example="Pedro"),
     *     @OA\Property(property="last_name", type="string", maxLength=255, description="Apellidos del paciente", example="Guill Ferri"),
     *     @OA\Property(
     *         property="birth_date",
     *         type="string",
     *         format="date",
     *         description="Data de naixement del paciente.",
     *         example="15-05-2002"
     *     ),
     *     @OA\Property(property="address", type="string", maxLength=255, description="Direccion del paciente", example="90819 Schamberger Parkway Apt"),
     *     @OA\Property(property="city", type="string", maxLength=255, description="Ciudad del paciente", example="North Madge"),
     *     @OA\Property(property="postal_code", type="integer", description="Codigo postal del paciente", example=03340),
     *     @OA\Property(property="dni", type="string", maxLength=255, description="Dni del paciente", example="36604710A"),
     *     @OA\Property(property="health_card_number", type="integer", description="Numero social", example=51166090),
     *     @OA\Property(property="phone", type="string", maxLength=255, description="Numero telefonico del paciente", example="+34 615 78 7729"),
     *     @OA\Property(property="email", type="string", maxLength=255, description="Correo electronico del paciente", example="kendrick63@gleichner.net"),
     *     @OA\Property(property="zone_id", type="integer", description="Id de la zona en la que vive", example=1),
     *     @OA\Property(property="user_id", type="integer", description="Id del operador", example=1),
     *     @OA\Property(property="personal_situation", type="string", maxLength=255, description="Situacion personal del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="health_situation", type="string", maxLength=255, description="Situacion de vida del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="housing_situation", type="string", maxLength=255, description="Situacion de vivienda del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="personal_autonomy", type="string", maxLength=255, description="Autonomia del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="economic_situation", type="string", maxLength=255, description="Situacion economica del paciente", example="Lorem ipsum")
     * )
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
