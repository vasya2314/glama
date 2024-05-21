<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'account_name' => $this->account_name,
            'login' => $this->login,
            'password' => $this->password,
            'client_id' => $this->client_id,
            'qty_campaigns' => $this->qty_campaigns,
            'balance' => kopToRub((int)$this->balance),
            'is_enable_shared_account' => $this->is_enable_shared_account,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
