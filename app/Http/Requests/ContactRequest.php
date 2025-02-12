<?php

namespace App\Http\Requests;

use App\Models\Contact;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
     /**
     * @OA\Schema(
     *     schema="ContactRequest",
     *     description="Validació per la creació y modificacio de contacts",
     *     required={
     *               "first_name", 
     *               "last_name",
     *               "phone",
     *               "patient_id",
     *               "relationship"
     *               },
     *     @OA\Property(property="first_name", type="string", maxLength=255, description="Nombre del contacto", example="Pedro"),
     *     @OA\Property(property="last_name", type="string", maxLength=255, description="Apellidos del contacto", example="Guill Ferri"),
     *     @OA\Property(property="phone", type="string", maxLength=255, description="Numero telefonico del contacto"),
     *     @OA\Property(property="patient_id", type="integer", description="Id del paciente"),
     *     @OA\Property(property="relationship", type="string", maxLength=255, description="Relacion personal con el paciente")
     * )
     */
    public function authorize(): bool
    {
       
        if ($this->user()->can('create', Contact::class) || $this->user()->can('update', Contact::class)) {
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
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'patient_id' => 'required|integer'
        ];
    }
}
