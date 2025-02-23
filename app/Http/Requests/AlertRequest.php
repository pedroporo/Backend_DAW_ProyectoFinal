<?php

namespace App\Http\Requests;

use App\Enums\Alarms_type;
use App\Models\Alert;
use Illuminate\Foundation\Http\FormRequest;

class AlertRequest extends FormRequest
{
   /**
     * @OA\Schema(
     *     schema="AlertRequest",
     *     description="Validació per la creació y modificacio de contacts",
     *     required={
     *               "zone_id", 
     *               "type",
     *               "phone",
     *               "start_date",
     *               "end_date"
     *               },
    *     @OA\Property(
     *         property="start_date",
     *         type="date",
     *         format="date",
     *         description="Data de inicio de la alarma.",
     *         example="15-05-2002"
     *     ),
     *     @OA\Property(
     *         property="end_date",
     *         type="date",
     *         format="date",
     *         description="Data de finalizacion de la alarma.",
     *         example="15-05-2002"
     *     ),
     *     @OA\Property(property="zone_id", type="integer", description="Id de la zona"),
     *     @OA\Property(property="type", type="string", description="Tipos permitidos",enum={\App\Enums\Alarms_type::class}),
     *     @OA\Property(property="description", type="string", description="Descripcion de la alarma")
     * 
     * )
     */
    public function authorize(): bool
    {
        if ($this->user()->can('create', Alert::class) || $this->user()->can('update', Alert::class)) {
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
            'zone_id' => 'required',
            //'type' => 'required|in:' . array_column(Alarms_type::cases(), 'name'),
            'start_date' => 'required',
            'end_date' => 'required'
        ];
    }
}
