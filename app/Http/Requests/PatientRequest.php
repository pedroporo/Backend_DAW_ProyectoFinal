<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * @OA\Schema(
     *     schema="PatientRequest",
     *     description="Validació per la creació y modificacio de pacients",
     *     required={
     *               "name", 
     *               "last_name",
     *               "birth_date",
     *               "address",
     *               "city",
     *               "postal_code",
     *               "dni",
     *               "health_card_number",
     *               "phone",
     *               "email",
     *               "zone_id",
     *               "personal_situation",
     *               "health_situation",
     *               "housing_situation",
     *               "personal_autonomy",
     *               "economic_situation"
     *               },
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
     *     @OA\Property(property="personal_situation", type="string", maxLength=255, description="Situacion personal del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="health_situation", type="string", maxLength=255, description="Situacion de vida del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="housing_situation", type="string", maxLength=255, description="Situacion de vivienda del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="personal_autonomy", type="string", maxLength=255, description="Autonomia del paciente", example="Lorem ipsum"),
     *     @OA\Property(property="economic_situation", type="string", maxLength=255, description="Situacion economica del paciente", example="Lorem ipsum")
     * )
     */
    public function authorize(): bool
    {
        if ($this->user()->can('create', Patient::class) || $this->user()->can('update', Patient::class)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //dd($this);
        //dd($this->route('patient')->id);
        return [
            'name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'dni' => 'required',
            'health_card_number' => 'required|unique:patients,id,'.$this->route('patient')->id,
            'phone' => 'required',
            'email' => 'required',
            'zone_id' => 'required',
            'personal_situation' => 'required',
            'health_situation' => 'required',
            'housing_situation' => 'required',
            'personal_autonomy' => 'required',
            'economic_situation' => 'required'

        ];
    }
}
