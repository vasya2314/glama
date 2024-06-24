<?php

namespace App\Http\Resources\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::select(['name', 'role'])->findOrFail($this->user_id);

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $user->name,
            'role' => $user->role,
            'ticket_id' => $this->ticket_id,
            'message' => $this->message,
            'media' => $this->getMedia('messages'),
            'created_at' => $this->created_at,
        ];
    }
}
