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
                'nullable',
                'unique:clients,contract_id',
                Rule::exists('contracts', 'id')->where(function ($query) {
                    $query->where('user_id', $this->user()->id);
                }),
            ],
        ];

        if ($this->is('api/v1/clients') && $this->isMethod('get'))
        {
            return [
                'account_name' => 'string',
                'date_start' => 'date',
                'date_end' => 'date',
            ];
        }

        if ($this->is('api/v1/clients') && $this->isMethod('post'))
        {
            return array_merge(
                $rules,
                [
                    'account_name' => 'required|string',
                    'login' => 'required|unique:clients,login',
                    'params' => 'required|array',
                ]
            );
        }

        if($this->is('api/v1/clients/*') && $this->isMethod('patch'))
        {
            return $rules;
        }

        return [];
    }

    protected function prepareForValidation(): void
    {
        if($this->has('login'))
        {
            $tinType = 'LEGAL';
            $tin = config('yandex')['inn_company'];

            $params = [
                'Login' => 'gl-' . $this->get('login'),
                'FirstName' => 'Имя',
                'LastName' => 'Фамилия',
                'Currency' => 'RUB',
                'Grants' => [
                    [
                        'Privilege' => 'EDIT_CAMPAIGNS',
                        'Value' => 'YES',
                    ],
                    [
                        'Privilege' => 'IMPORT_XLS',
                        'Value' => 'YES',
                    ],
                    [
                        'Privilege' => 'TRANSFER_MONEY',
                        'Value' => 'YES',
                    ]
                ],
                'Notification' => [
                    'Lang' => 'RU',
                    'Email' => request()->user()->email,
                    'EmailSubscriptions' => [
                        [
                            'Option' => 'RECEIVE_RECOMMENDATIONS',
                            'Value' => 'YES',
                        ],
                        [
                            'Option' => 'TRACK_MANAGED_CAMPAIGNS',
                            'Value' => 'YES',
                        ],
                        [
                            'Option' => 'TRACK_POSITION_CHANGES',
                            'Value' => 'YES',
                        ],
                    ]
                ],
                'Settings' => [
                    [
                        'Option' => 'CORRECT_TYPOS_AUTOMATICALLY',
                        'Value' => 'NO',
                    ],
                    [
                        'Option' => 'DISPLAY_STORE_RATING',
                        'Value' => 'NO',
                    ]
                ],
                'TinInfo' => [
                    'TinType' => $tinType,
                    'Tin' => $tin,
                ]
            ];

            $this->merge([
                'params' => $params,
            ]);
        }
    }

    protected function getContractType(Contract $contract): string
    {
        $contractType = $contract->contractable_type;

        return match ($contractType) {
            $contract::LEGAL_ENTITY => 'LEGAL',
            $contract::NATURAL_PERSON => 'PHYSICAL',
            $contract::INDIVIDUAL_ENTREPRENEUR => 'INDIVIDUAL',
            default => "NONE",
        };

    }

    public function updateClient(): array
    {
        return [
            'contract_id' => $this->get('contract_id'),
        ];
    }

    public function storeClient(array $params): array
    {
        return array_merge(
            $params,
            [
                'qty_campaigns' => 0,
                'balance' => 0,
            ]
        );
    }

    public function messages(): array
    {
        return [
            'contract_id.unique' => __('The selected :attribute already belongs to the client.'),
        ];
    }
}
