<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClosingDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $contract = $this->contract()->select(['id', 'display_name'])->firstOrFail();
        $closingAct = $this->closingAct()->select(['id', 'date_generated', 'act_number', 'amount', 'amount_nds'])->firstOrFail();
        $closingInvoice = $this->closingInvoice()->select(['id', 'date_generated', 'amount', 'amount_nds'])->firstOrFail();

        return [
            'id' => $this->id,
            'contract' => [
                'id' => $contract->id,
                'name' => $contract->display_name,
            ],
            'closing_invoice' => [
                'id' => $closingInvoice->id,
                'date_generated' => $closingInvoice->date_generated,
                'amount' => kopToRub($closingInvoice->amount),
                'amount_nds' => kopToRub($closingInvoice->amount_nds),
            ],
            'closing_act' => [
                'id' => $closingAct->id,
                'date_generated' => $closingAct->date_generated,
                'number' => $closingAct->act_number,
                'amount' => kopToRub($closingAct->amount),
                'amount_nds' => kopToRub($closingAct->amount_nds),
            ],
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
