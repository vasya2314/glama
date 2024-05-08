<?php

namespace App\Http\Requests\v1;

use App\Models\Contract;
use App\Models\IndividualEntrepreneur;
use App\Models\LegalEntity;
use App\Models\NaturalPerson;
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

    public function getTypeContract(): ?string
    {
        if($this->contract_type == Contract::LEGAL_ENTITY)
        {
            return LegalEntity::class;
        }

        if($this->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR)
        {
            return IndividualEntrepreneur::class;
        }

        if($this->contract_type == Contract::NATURAL_PERSON)
        {
            return NaturalPerson::class;
        }

        return null;
    }

    public function storeEntityContract(): array
    {
        $data = $this->validated();
        unset($data['contract_type']);
        unset($data['client_id']);

        return $data;
    }

    public function updateEntityContract(): array
    {
        $data = $this->validated();
        unset($data['client_id']);

        return $data;
    }

    public function storeContract(): array
    {
        $result = ['user_id' => $this->user()->id];
        if($this->request->has('client_id')) $result['client_id'] = $this->client_id;

        return $result;
    }

    public function updateContract(): array
    {
        $result = [];
        if($this->request->has('client_id')) $result['client_id'] = $this->client_id;

        return $result;
    }

    private function includeRules(): array
    {
        if(
            $this->contract_type == Contract::LEGAL_ENTITY ||
            isset($this->contract->contractable_type) &&
            $this->contract->contractable_type == Contract::LEGAL_ENTITY
        )
        {
            return $this->rulesLegalEntityRequest();
        }

        if(
            $this->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR ||
            isset($this->contract->contractable_type) &&
            $this->contract->contractable_type == Contract::INDIVIDUAL_ENTREPRENEUR
        )
        {
            return $this->rulesIndividualEntrepreneurRequest();
        }

        if(
            $this->contract_type == Contract::NATURAL_PERSON ||
            isset($this->contract->contractable_type) &&
            $this->contract->contractable_type == Contract::NATURAL_PERSON
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
            'pick_up' => 'required|string|in:not_need,need_original,need_email',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.unique' => __('The selected :attribute already has an attached contract'),
        ];
    }

}


