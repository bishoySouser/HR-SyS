<?php

namespace App\Http\Requests\ItTicket;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItTicketRequest extends FormRequest
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
        return [
            'employee_id' => 'required|exists:employes,id', // Optional for updates
            'title' => 'sometimes|required|string', // Only required if provided for update
            'category' => 'sometimes|required|in:computer,email,network,phone,other', // Only required if provided for update
            'describe' => 'sometimes|required|string', // Only required if provided for update
            'comment' => 'nullable|string',
            'phone' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'wait_accountant' => 'sometimes|boolean', // Optional for updates
            'status' => 'nullable|in:pending,in progress,done', // Optional for updates
        ];
    }
}
