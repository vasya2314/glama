<?php

namespace App\Http\Requests\v1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends FormRequest
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
        if ($this->is('api/v1/agency/user/login') && $this->isMethod('POST'))
        {
            return [
                'user_id' => [
                    'required',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('parent_id', $this->user()->id);
                    }),
                ],
            ];
        }

        if (
            (
                $this->is('api/v1/register') ||
                $this->is('api/v1/admin/users/agency-user/create') ||
                $this->is('api/v1/agency/users/create-user')
            ) &&
            $this->isMethod('POST')
        )
        {
            return [
                'name' => 'nullable|string|max:30',
                'lastname' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:30|unique:users,phone',
                'contact_email' => 'required|string|email',
                'email' => 'required|email|unique:users|max:255',
                'password' => [
                    'required', 'string', 'confirmed',
                    Password::min(8)
                        ->numbers()
                        ->symbols()
                ],
            ];
        }

        if ($this->is('api/v1/login') && $this->isMethod('POST'))
        {
            return [
                'email' => 'required|email',
                'password' => 'required|string',
            ];
        }

        if ($this->is('api/v1/forgot-password') && $this->isMethod('POST'))
        {
            return [
                'email' => 'required|email',
            ];
        }

        if ($this->is('api/v1/reset-password') && $this->isMethod('POST'))
        {
            return [
                'token' => 'required',
                'email' => 'required|email',
                'password' => [
                    'required', 'string', 'confirmed',
                    Password::min(8)
                        ->numbers()
                        ->symbols()
                ]
            ];
        }

        return [];
    }

    public function validatedData(string $userType, int|null $parentId): array
    {
        return array_merge(
            $this->validated(),
            [
                'balance' => 0,
                'role' => User::ROLE_USER,
                'user_type' => $userType,
                'password' => Hash::make($this->validated()['password']),
                'parent_id' => $parentId,
            ]
        );
    }
}
