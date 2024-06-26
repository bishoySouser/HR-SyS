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
        $rules = [
            'full_name' => 'required|string',
            'profile_pic' => 'nullable|file|mimes:jpeg,png,webp|max:2048',
            'phone_number' => 'nullable|string|max:20',
            'national_id' => 'required|string',
            'birthday' => 'required|date',
            'location' => 'required|string',
            'education' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'contract_periods' => 'required|string',
            'hire_date' => 'nullable|date',
            'job_id' => 'required|exists:jobs,id',
            'salary' =>  ['required', 'numeric', new SalaryRange($this->job_id)],
            'manager_id' => 'nullable|exists:employes,id',
            'department_id' => 'exists:departments,id',
        ];

        if ($this->getMethod() == 'POST') {
            // Include email validation for new employee creation
            $rules['email'] = 'required|email|unique:employes,email';
        } else if ($this->getMethod() == 'PUT' || $this->getMethod() == 'PATCH') {
            // Exclude email validation for existing employee updates
            $rules['email'] = 'sometimes|email|unique:employes,email,' . $this->id;
        }

        return $rules;
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
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be a number.',
            'salary.salary_range' => "Salary must be between :min and :max for this job.", // New message key
        ];
    }
}
