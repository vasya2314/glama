<?php

namespace App\Http\Requests\v1;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
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
        if ($this->is('api/v1/tickets') && $this->isMethod('post'))
        {
            return [
                'title' => 'required|string|max:255',
                'message' => 'required|string',
            ];
        }

        if ($this->is('api/v1/tickets/*/change-status') && $this->isMethod('patch'))
        {
            return [
                'status' => 'required|in:' . implode(',', Ticket::getAllStatuses()),
            ];
        }

        if ($this->is('api/v1/tickets/*/assigned-to') && $this->isMethod('patch'))
        {
            return [
                'assigned_to' => [
                    'required',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', User::ROLE_ADMIN);
                    }),
                ],
            ];
        }

        return [];

    }

    public function storeValidatedData(): array
    {
        $data = $this->validated();
        if(isset($data['message'])) unset($data['message']);

        return array_merge(
            $data,
            [
                'status' => Ticket::STATUS_OPEN,
                'assigned_to' => null,
            ]
        );
    }

    public function storeMessage(Ticket $ticket, string $message): array
    {
        return [
            'user_id' => $this->user(),
            'ticket_id' => $ticket->id,
            'message' => $message,
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => __('title'),
            'message' => __('message'),
            'status' => __('status'),
            'assigned_to' => __('assigned to'),
        ];
    }

}
