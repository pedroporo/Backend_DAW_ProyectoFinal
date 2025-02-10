<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="ZoneResource",
     *     description="Esquema del recurs Zone",
     *      @OA\Property(property="name", type="string", maxLength=255, description="Nombre de la zona", example="439 Karley Loaf Suite 897")
     * )
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
