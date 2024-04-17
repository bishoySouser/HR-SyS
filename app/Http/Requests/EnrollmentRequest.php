<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentRequest extends FormRequest
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
            // 'course_id' => 'required|exists:courses,id',
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments')->where(function ($query) {
                    return $query->where('course_id', $this->input('course_id'))
                                 ->where('employee_id', $this->input('employee_id'));
                }),
            ],

            'employee_id' => [
                'required',
                'exists:employes,id',
                Rule::unique('enrollments')->where(function ($query) {
                    return $query->where('course_id', $this->input('course_id'))
                                 ->where('employee_id', $this->input('employee_id'));
                }),
            ],

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
            'course_id.unique' => 'The course has already been assigned to that employee.',
            'employee_id.unique' => 'This employee has already been enrolled in that course.',
        ];
    }
}
