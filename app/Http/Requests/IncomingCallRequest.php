<?php

namespace App\Http\Requests;

use App\Models\IncomingCall;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class IncomingCallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //De momento comento esto solo para comprovaciones

        // if ($this->user()->can('create', IncomingCall::class) || $this->user()->can('update', IncomingCall::class)) {
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
        return [
            'timestamp' => ['required', 'date'],
            'patient_id' => ['required', 'exists:patients,id'],
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in([
                'social_emergency', 'medical_emergency', 'loneliness_crisis', 
                'unanswered_alarm', 'absence_notification', 'data_update', 
                'accidental', 'info_request', 'complaint', 'social_call', 
                'medical_appointment', 'other'
            ])],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
