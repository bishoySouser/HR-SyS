<?php

namespace App\Http\Requests\ItTicket;

use Illuminate\Foundation\Http\FormRequest;

class StoreItTicketRequest extends FormRequest
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
            'employee_id' => 'required|exists:employes,id', // Optional for creating new tickets
            'title' => 'required|string',
            'category' => 'required|in:computer,email,network,phone,other',
            'describe' => 'required|string',
            'comment' => 'nullable|string',
            'phone' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'wait_accountant' => 'boolean',
        ];
    }
}
