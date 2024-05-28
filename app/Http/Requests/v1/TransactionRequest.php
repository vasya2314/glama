<?php

namespace App\Http\Requests\v1;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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

        if ($this->is('api/v1/transactions') && $this->isMethod('get'))
        {
            return [
                'type' => 'in:' . implode(',', Transaction::getAllTypes()),
                'date_start' => 'date',
                'date_end' => 'date',
            ];
        }

        return [];
    }
}
