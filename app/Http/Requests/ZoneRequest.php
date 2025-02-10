<?php

namespace App\Http\Requests;

use App\Models\Zone;
use Illuminate\Foundation\Http\FormRequest;

class ZoneRequest extends FormRequest
{
    /**
     * @OA\Schema(
     *     schema="ZoneRequest",
     *     description="Validació per la creació y modificacio de zones",
     *     required={
     *               "name"
     *               },
     *     @OA\Property(property="name", type="string", maxLength=255, description="Nombre de la zona", example="439 Karley Loaf Suite 897")
     *     
     * )
     */
    public function authorize(): bool
    {
        if ($this->user()->can('create', Zone::class) || $this->user()->can('update', Zone::class)) {
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
            'name' => 'required|unique:zones|max:255',
        ];
    }
}
