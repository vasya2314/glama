<?php

namespace App\Http\Requests\v1;

use App\Models\Contract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContractRequest extends FormRequest
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
//        $rules = [
//            'client_id' => [
//                'nullable',
//                Rule::exists('clients', 'id')->where(function ($query) {
//                    $query->where('user_id', $this->user()->id);
//                }),
//                Rule::unique('contracts', 'client_id'),
//            ]
//        ];
        $rules = [];

        if ($this->is('api/v1/contracts') && $this->isMethod('post'))
        {
            $rules['contract_type'] = 'required|in:' . implode(',', Contract::getAllTypes());
            return array_merge($rules, $this->includeRules());
        }

        if ($this->is('api/v1/contracts/*') && $this->isMethod('patch'))
        {
            return array_merge($rules, $this->includeRules());
        }

        return [];
    }

    public function storeContract(): array
    {
        $data = $this->validated();
        unset($data['contract_type']);

        return [
            'contract_type' => $this->request->get('contract_type'),
            'display_name' => $this->generateDisplayName($this->request->get('contract_type')),
            'data' => json_encode($data),
        ];
    }

    public function updateContract(): array
    {
        $data = $this->validated();
        unset($data['contract_type']);

        return [
            'display_name' => $this->generateDisplayName($this->contract->contract_type),
            'data' => json_encode($data),
        ];
    }

    private function includeRules(): array
    {
        if(
            $this->contract_type == Contract::LEGAL_ENTITY ||
            isset($this->contract) &&
            $this->contract->contract_type == Contract::LEGAL_ENTITY
        )
        {
            return $this->rulesLegalEntityRequest();
        }

        if(
            $this->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR ||
            isset($this->contract) &&
            $this->contract->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR
        )
        {
            return $this->rulesIndividualEntrepreneurRequest();
        }

        if(
            $this->contract_type == Contract::NATURAL_PERSON ||
            isset($this->contract) &&
            $this->contract->contract_type == Contract::NATURAL_PERSON
        )
        {
            return $this->rulesNaturalPersonRequest();
        }

        return [];

    }

    private function rulesLegalEntityRequest(): array
    {
        return [
            'inn' => [
                'required',
                'numeric',
                'regex:/^(\\d{12}|\\d{10})$/',
            ],
            'kpp' => [
                'required',
                'numeric',
                'regex:/^(\\d{9})$/',
            ],
            'ogrn' => [
                'required',
                'numeric',
                'regex:/^\\d{13}$/',
            ],
            'company_name' => 'required|string|max:512',
            'legal_address' => 'required|string',
            'actual_address' => 'required|string',
            'contact_face' => 'required|string',
            'job_title' => 'required|string',
            'phone' => [
                'required',
                'string',
                'regex:/^((\\+7)([0-9]){10})$/',
            ],
            'email' => 'required|string|email',
            'bik' => 'required|numeric',
            'checking_account' => 'required|numeric',
            'bank_name' => 'required|string',
            'correspondent_account' => [
                'required',
                'string',
                'regex:/^(\\d{20}|\\d{22})$/',
            ],
            'pick_up' => 'required|string|in:not_need,need_original,need_email',
            'is_same_legal_address' => 'required|boolean',
        ];
    }

    private function rulesIndividualEntrepreneurRequest(): array
    {
        return [
            'inn' => [
                'required',
                'numeric',
                'regex:/^(\\d{12}|\\d{10})$/',
            ],
            'ogrnip' => [
                'required',
                'numeric',
                'regex:/^\\d{15}$/',
            ],
            'kpp' => [
                'required',
                'numeric',
            ],
            'company_name' => 'required|string|max:512',
            'legal_address' => 'required|string',
            'actual_address' => 'required|string',
            'contact_face' => 'required|string',
            'job_title' => 'required|string',
            'phone' => [
                'required',
                'string',
                'regex:/^((\\+7)([0-9]){10})$/',
            ],
            'email' => 'required|string|email',
            'bik' => 'required|numeric',
            'checking_account' => 'required|numeric',
            'bank_name' => 'required|string',
            'correspondent_account' => [
                'required',
                'string',
                'regex:/^(\\d{20}|\\d{22})$/',
            ],
            'pick_up' => 'required|string|in:not_need,need_original,need_email',
            'is_same_legal_address' => 'required|boolean',
        ];
    }

    private function rulesNaturalPersonRequest(): array
    {
        return [
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'surname' => 'nullable|string',
            'inn' => [
                'required',
                'numeric',
                'regex:/^(\\d{12}|\\d{10})$/',
            ],
            'address' => 'required|string',
            'phone' => [
                'required',
                'string',
                'regex:/^((\\+7)([0-9]){10})$/',
            ],
            'email' => 'required|string|email',
            'pick_up' => 'required|string|in:not_need,need_original,need_email',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.unique' => __('The selected :attribute already has an attached contract'),
        ];
    }

    private function generateDisplayName(string $contractType): string
    {
        if($contractType == Contract::NATURAL_PERSON)
        {
            $displayName = $this->request->get('lastname')  . ' ' . $this->request->get('firstname');
            if($this->request->get('surname')) $displayName .= ' ' . $this->request->get('surname');
        } else {
            $displayName = $this->request->get('company_name');
        }

        return $displayName;
    }

    public function attributes(): array
    {
        return [
            'inn' => __('inn'),
            'kpp' => __('kpp'),
            'ogrnip' => __('ogrnip'),
            'ogrn' => __('ogrn'),
            'company_name' => __('company name'),
            'legal_address' => __('legal address'),
            'actual_address' => __('actual address'),
            'contact_face' => __('contact face'),
            'job_title' => __('job title'),
            'phone' => __('phone'),
            'email' => __('email'),
            'bik' => __('bik'),
            'checking_account' => __('checking account'),
            'bank_name' => __('bank name'),
            'correspondent_account' => __('correspondent account'),
            'pick_up' => __('pick up'),
            'is_same_legal_address' => __('is same legal address'),
            'address' => __('address'),
            'lastname' => __('lastname'),
            'firstname' => __('firstname'),
            'surname' => __('surname'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if(
            $this->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR ||
            isset($this->contract) &&
            $this->contract->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR
        ) {
            $this->merge([
                'kpp' => 0,
            ]);
        }
    }

}


