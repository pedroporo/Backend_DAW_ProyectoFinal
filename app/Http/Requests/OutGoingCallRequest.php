<?php

namespace App\Http\Requests;

use App\Models\OutgoingCall;
use Illuminate\Foundation\Http\FormRequest;

class OutGoingCallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Comentado por problemas de autorizacion
        // if ($this->user()->can('create', OutgoingCall::class) || $this->user()->can('update', OutgoingCall::class)) {
        //     return true;
        // } else {
        //     return false;
        // }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //cambiar si es planificada tindra una alarma sino no
        return [
            'timestamp' => ['required', 'date'],
            'patient_id' => ['required', 'exists:patients,id'],
            'user_id' => ['required', 'exists:users,id'],
            'is_planned' => ['required', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
            'alarm_id' => ['required_if:is_planned,true', 'nullable', 'exists:alerts,id'],
        ];
    }
}
