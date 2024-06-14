<?php

namespace App\Http\Requests\v1;

use App\Classes\Tinkoff;
use App\Models\BalanceAccount;
use App\Models\Operation;
use App\Models\PaymentInvoice;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DepositRequest extends FormRequest
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
        $rules = [
            'contract_id' => [
                'required',
                Rule::exists('contracts', 'id')->where(function ($query) {
                    $query->where('user_id', $this->user()->id);
                }),
            ],
            'amount' => 'required|numeric',
        ];

        if ($this->is('api/v1/user/deposit/payment') && $this->isMethod('post'))
        {
            return array_merge(
                $rules,
                [
                    'method_type' => 'in:' . Transaction::METHOD_TYPE_QR .',' . Transaction::METHOD_TYPE_CARD,
                    'amount_deposit' => 'required|numeric'
                ]
            );
        }

        if ($this->is('api/v1/user/deposit/invoice') && $this->isMethod('post'))
        {
            return $rules;
        }

        return [];
    }

    public function storeNaturalPersonTransaction(string $transactionType, Request $request, Tinkoff $tinkoff): array
    {
        $data = json_decode($tinkoff->response);

        return [
            'type' => $transactionType,
            'status' => $data->Status,
            'payment_id' => $data->PaymentId,
            'order_id' => $data->OrderId,
            'amount_deposit' => (int)$request->get('amount_deposit'),
            'amount' => (int)$data->Amount,
            'method_type' => $request->get('method_type'),
            'balance_account_type' => BalanceAccount::BALANCE_MAIN,
        ];
    }

    public function storeInvoiceTransaction(): array
    {
        return [
            'type' => Transaction::TYPE_DEPOSIT,
            'status' => Transaction::STATUS_NEW,
            'payment_id' => null,
            'order_id' => null,
            'amount_deposit' => (int)request()->get('amount'),
            'amount' => (int)request()->get('amount'),
            'method_type' => Transaction::METHOD_TYPE_INVOICE,
            'balance_account_type' => BalanceAccount::BALANCE_MAIN,
        ];
    }

    public function updateInvoiceTransaction($data, string $status): array
    {
        return [
            'status' => $status,
            'payment_id' => null,
            'order_id' => $data->invoiceId,
            'method_type' => 'invoice',
            'data' => json_encode($data),
        ];
    }

    public function attributes(): array
    {
        return [
            'amount' => __('amount'),
            'amount_deposit' => __('amount base'),
            'method_type' => __('method type'),
        ];
    }

}
