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
        $response = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'display_name' => $this->display_name,
        ];

        if($request->has('short_query'))
        {
            return $response;
        }

        return array_merge(
            $response,
            [
                'contract_type' => $this->contractable_type,
                'contract_details' => ContractPolymorphicResource::make($this->contractable),
                'created_at' => $this->created_at->toDateTimeString(),
            ]
        );

    }
}
