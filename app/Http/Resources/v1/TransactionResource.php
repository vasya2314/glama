<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'transactionable_type' => $this->transactionable_type,
            'transactionable_id' => $this->transactionable_id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'status' => $this->status,
            'amount_deposit' => $this->amount_deposit,
            'amount' => $this->amount,
            'method_type' => $this->method_type,
            'balance_account_type' => $this->balance_account_type,
            'created_at' => $this->created_at,
        ];
    }
}
