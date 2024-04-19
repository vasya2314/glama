<?php

namespace App\Http\Requests\v1;

use App\Classes\Tinkoff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->is('api/v1/user/deposit') && $this->isMethod('post'))
        {
            return [
                'contract_id' => [
                    'required',
                    Rule::exists('contracts', 'id')->where(function ($query) {
                        $query->where('user_id', $this->user()->id);
                    }),
                ],
                'amount' => 'required|numeric',
            ];
        }

        return [];
    }

    public function storeOperation(string $type): array
    {
        return array_merge(
            $this->validated(),
            [
                'type' => $type,
            ]
        );
    }

    public function storeTransaction(Tinkoff $tinkoff): array
    {
        $data = json_decode($tinkoff->response);

        return [
            'status' => $data->Status,
            'payment_id' => $data->PaymentId,
            'order_id' => $data->OrderId,
            'amount' => $data->Amount,
        ];
    }

}
