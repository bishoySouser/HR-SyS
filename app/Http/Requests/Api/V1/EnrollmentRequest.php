<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class EnrollmentRequest extends FormRequest
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
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments')->where(function ($query) {
                    return $query->where('course_id', $this->input('course_id'))
                                 ->where('employee_id', Auth::id());
                }),
            ],
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('courseId')) {
            $this->merge([
                'course_id' => $this->input('courseId'),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $courseId = $this->input('courseId');
            $employeeId = Auth::id();

            $exists = \DB::table('enrollments')
                        ->where('course_id', $courseId)
                        ->where('employee_id', $employeeId)
                        ->exists();

            if ($exists) {
                $validator->errors()->add('course_id', 'You are already enrolled in this course.');
            }
        });
    }
}
