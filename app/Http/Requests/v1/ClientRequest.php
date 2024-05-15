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
                'unique:clients,contract_id',
                Rule::exists('contracts', 'id')->where(function ($query) {
                    $query->where('user_id', $this->user()->id);
                }),
            ],
        ];

        if ($this->is('api/v1/clients') && $this->isMethod('post'))
        {
            return array_merge($rules, [
                'account_name' => 'required|string|unique:clients,id',
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
        $email = isset($this->get('params')['Notification']['Email']) ? $this->get('params')['Notification']['Email'] : '';

        if($email && $this->has('contract_id') && $this->has('params'))
        {
            $contract = Contract::findOrFail($this->contract_id);

            $grants = [
                [
                    'Privilege' => 'EDIT_CAMPAIGNS',
                    'Value' => 'YES',
                ]
            ];

            $merge = $this->all();
            $merge['params']['Login'] = 'gl-' . $this->modifyEmail($email);
            $merge['params']['TinInfo']['Tin'] = $contract->contractable->inn;
            $merge['params']['Currency'] = 'RUB';
            $merge['params']['Grants'] = $grants;

            $this->merge($merge);
        }
    }

    protected function modifyEmail(string $email): string
    {
        $str = strpos($email, "@");
        $email = substr($email, 0, $str);
        $result = preg_replace('/\./', '-', $email);

        return strtolower($result);
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
