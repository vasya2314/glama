<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        if ($this->is('api/v1/user/update-user'))
        {
            return [
                'name' => 'nullable|string|max:30',
                'lastname' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:30',
                'contact_email' => 'sometimes|required|string|email',
            ];
        }

        if ($this->is('api/v1/user/change-password'))
        {
            return [
                'current_password' => 'required|string|current_password',
                'new_password' => [
                    'required', 'string', 'confirmed', 'different:current_password',
                    Password::min(8)
                        ->numbers()
                        ->symbols()
                ]
            ];
        }

        if ($this->is('api/v1/user/withdrawal-money'))
        {
            return [
                'amount' => 'required|numeric',
            ];
        }

        return [];
    }

    public function validatedUser(): array
    {
        return $this->validated();
    }

    public function attributes(): array
    {
        return [
            'name' => __('firstname'),
            'lastname' => __('lastname'),
            'phone' => __('phone'),
            'contact_email' => __('contact email'),
            'current_password' => __('current password'),
            'new_password' => __('new password'),
        ];
    }

}
