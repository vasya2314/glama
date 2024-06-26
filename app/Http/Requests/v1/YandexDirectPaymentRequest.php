<?php

namespace App\Http\Requests\v1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class YandexDirectPaymentRequest extends FormRequest
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
        if ($this->is('api/v1/admin/yandex-direct/return-money') && $this->isMethod('post'))
        {
            return [
                'amount' => 'required',
                'contract_id' => 'required|exists:contracts,id',
            ];
        }

        if ($this->is('api/v1/user/yandex-direct/deposit') && $this->isMethod('post'))
        {
            return [
                'amount_deposit' => 'required|numeric',
                'amount' => 'required|numeric',
                'client_id' => [
                    'required',
                    Rule::exists('clients', 'id')->where(function ($query) {
                        $query->where('user_id', $this->user()->id);
                    }),
                ],
            ];
        }

        return [];
    }
}
