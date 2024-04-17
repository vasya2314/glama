<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
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
            'user_id' => $this->user_id,
            'contract_type' => $this->contractable_type,
            'contract_details' => ContractPolymorphicResource::make($this->contractable),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
