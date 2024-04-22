<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'nullable|exists:employes,id',
            'title' => 'required|string',
            'category' => 'required|in:computer,email,network,phone,other',
            'describe' => 'required|string',
            'comment' => 'nullable|string',
            'phone' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'wait_accountant' => 'boolean',
            'status' => 'nullable|in:pending,in progress,done',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
