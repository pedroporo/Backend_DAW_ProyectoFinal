<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomingCallResource extends JsonResource
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
            'patient_id' => $this->patient_id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'description' => $this->description,
            'timestamp' => $this->timestamp,
            'formatted_timestamp' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
