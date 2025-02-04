<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        return [

            'name'=> 'required',
            'last_name'=> 'required',
            'birth_date'=> 'required',
            'address'=> 'required',
            'city'=> 'required',
            'postal_code'=> 'required|integer',
            'dni'=> 'required|integer',
            'health_card_number'=> 'required|unique:patients',
            'phone'=> 'required',
            'email'=> 'required',
            'zone_id'=> 'required|integer',
            'user_id'=> 'required|integer',
            'personal_situation'=> 'required',
            'health_situation'=> 'required',
            'housing_situation'=> 'required',
            'personal_autonomy'=> 'required',
            'economic_situation'=> 'required'

        ];
    }
}
