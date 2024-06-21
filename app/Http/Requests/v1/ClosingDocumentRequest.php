<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClosingDocumentRequest extends FormRequest
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
        if ($this->is('api/v1/user/closing-acts/pdf') && $this->isMethod('POST'))
        {
            return [
                'closing_act_id' => [
                    'required',
                    Rule::exists('closing_documents', 'closing_act_id')->where(function ($query) {
                        $query->where('user_id', $this->user()->id);
                    }),
                ],
            ];
        }

        return [];
    }
}
