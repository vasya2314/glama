<?php

namespace App\Http\Requests\v1;

use App\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
                'unique:clients,id',
                Rule::exists('contracts', 'id')->where(function ($query) {
                    $query->where('user_id', $this->user()->id);
                }),
            ],
        ];

        if ($this->is('api/v1/clients') && $this->isMethod('post'))
        {
            return array_merge($rules, [
                'glama_account_name' => 'required|string|unique:clients,id',
                'params' => 'required',
            ]);
        }

        if($this->is('api/v1/clients/*') && $this->isMethod('patch'))
        {
            return $rules;
        }

        return [];
    }

    protected function prepareForValidation(): void
    {
        if($this->has('glama_account_name') && $this->has('contract_id') && $this->has('params'))
        {
            $contract = Contract::findOrFail($this->contract_id);

            $merge = $this->all();
            $merge['glama_account_name'] = $this->glama_account_name . '-glama' . mt_rand(100000, 999999);
            $merge['params']['TinInfo']['Tin'] = $contract->contractable->inn;

            $this->merge($merge);
        }
    }

    public function updateClient(): array
    {
        return [
            'contract_id' => $this->get('contract_id'),
        ];
    }

    public function messages(): array
    {
        return [
            'contract_id.unique' => __('The selected :attribute already belongs to the client.'),
        ];
    }

}
