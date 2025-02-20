<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CallResource",
 *     title="Call Resource",
 *     description="Estructura de datos de una llamada desde el recurso",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=5, description="ID del usuario asociado a la llamada"),
 *     @OA\Property(property="status", type="string", example="completed", description="Estado de la llamada"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-19T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-19T12:34:56Z")
 * )
 */
class CallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
