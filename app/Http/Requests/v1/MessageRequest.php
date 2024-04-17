<?php

namespace App\Http\Requests\v1;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MessageRequest extends FormRequest
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
        if ($this->is('api/v1/tickets/*/messages') && $this->isMethod('post'))
        {
            return [
                'message' => 'required|string',
            ];
        }

        if ($this->is('api/v1/messages/*') && $this->isMethod('patch'))
        {
            return [
                'message' => 'required|string',
            ];
        }

        return [];

    }

    public function storeValidatedData(): array
    {
        return array_merge(
            $this->validated(),
            [
                'ticket_id' => $this->ticket->id,
            ]
        );
    }

}
