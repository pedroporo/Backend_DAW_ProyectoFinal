<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="ContactResource",
     *     description="Esquema del recurs Contact",
     *     @OA\Property(property="first_name", type="string", maxLength=255, description="Nombre del contacto", example="Pedro"),
     *     @OA\Property(property="last_name", type="string", maxLength=255, description="Apellidos del contacto", example="Guill Ferri"),
     *     @OA\Property(property="phone", type="string", maxLength=255, description="Numero telefonico del contacto"),
     *     @OA\Property(property="patient_id", type="integer", description="Id del paciente"),
     *     @OA\Property(property="relationship", type="string", maxLength=255, description="Relacion personal con el paciente")
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'patient' => new PatientResource($this->patient),
            'relationship' => $this->relationship,
        ];
    }
}
