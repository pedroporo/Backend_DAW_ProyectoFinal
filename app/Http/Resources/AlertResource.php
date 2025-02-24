<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="AlertResource",
     *     description="Esquema del recurs Alert",
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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'zone' => new ZoneResource($this->zone),
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'weekday' => $this->weekday,
            'description' => $this->description,
        ];
    }
}
