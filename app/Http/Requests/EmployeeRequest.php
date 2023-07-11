<?php

namespace App\Http\Requests;

use App\Rules\SalaryRange;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'full_name' => 'required|string',
            'email' => 'required|email|unique:employes,email',
            'phone_number' => 'nullable|string|max:20',
            'national_id' => 'required|string',
            'birthday' => 'required|date',
            'location' => 'required|string',
            'gender' => 'required|in:male,female',
            'contract_period' => 'required|string',
            'hire_date' => 'nullable|date',
            'grades' => 'required|in:junior,associate,senior',
            'top_management' => 'required|in:ceo,operation director,manager,employee',
            'job_id' => 'required|exists:jobs,id',
            'salary' =>  ['required', 'numeric', new SalaryRange($this->job_id)],
            'manager_id' => 'nullable|exists:employes,id',
            'department_id' => 'exists:departments,id',
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
