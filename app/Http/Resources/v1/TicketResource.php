<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'user_id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'assigned_to' => $this->assigned_to,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
