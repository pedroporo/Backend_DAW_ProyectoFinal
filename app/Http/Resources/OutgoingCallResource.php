<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutgoingCallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'patient_id' => $this->patient_id,
            'user_id' => $this->user_id,
            'is_planned' => $this->is_planned,
            'alarm_id' => $this->alarm_id,
            'description' => $this->description,
        ];
    }
}
