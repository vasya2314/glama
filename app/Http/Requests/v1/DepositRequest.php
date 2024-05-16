<?php

namespace App\Http\Requests\v1;

use App\Classes\Tinkoff;
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
                    'method_type' => 'in:qr,card',
                    'amount_base' => 'required|numeric'
                ]
            );
        }

        if ($this->is('api/v1/user/deposit/invoice') && $this->isMethod('post'))
        {
            return $rules;
        }

        return [];
    }

    public function storePaymentInvoice(): array
    {
        return [
            'status' => PaymentInvoice::STATUS_NEW,
        ];
    }

    public function updatePaymentInvoice($data, string $status = null): array
    {
        $resource = [
            'pdf_url' => $data->pdfUrl,
            'invoice_id' => $data->invoiceId,
        ];

        if($status) $resource['status'] = $status;

        return $resource;
    }

    public function storeNaturalPersonTransaction(string $transactionType, Request $request, Tinkoff $tinkoff): array
    {
        $data = json_decode($tinkoff->response);

        return [
            'type' => $transactionType,
            'status' => $data->Status,
            'payment_id' => $data->PaymentId,
            'order_id' => $data->OrderId,
            'amount_base' => (int)$request->get('amount_base'),
            'amount' => (int)$data->Amount,
            'method_type' => $request->get('method_type'),
        ];
    }

    public function storeInvoiceTransaction($data, Request $request): array
    {
        return [
            'type' => Transaction::TYPE_DEPOSIT_INVOICE,
            'status' => Transaction::STATUS_NEW,
            'payment_id' => null,
            'order_id' => $data->invoiceId,
            'amount_base' => (int)$request->get('amount'),
            'amount' => (int)$request->get('amount'),
            'method_type' => 'invoice',
        ];
    }

    public function attributes(): array
    {
        return [
            'amount' => __('amount'),
            'amount_base' => __('amount base'),
            'method_type' => __('method type'),
        ];
    }

}
