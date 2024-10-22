<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeesEvaluationRequest extends FormRequest
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
            'employee_id' => 'required|exists:employes,id',
            'evaluation_type' => 'required|in:quarter_1,quarter_2,quarter_3,quarter_4,end_of_probation,end_of_year',

            // Approach to work
            'follows_instructions' => 'required|integer|between:1,4',
            'accepts_constructive_criticism' => 'required|integer|between:1,4',
            'flexible_&_adaptable' => 'required|integer|between:1,4',

            // Technical Skills
            'job_knowledge' => 'required|integer|between:1,4',
            'follows_procedures' => 'required|integer|between:1,4',
            'works_full_potential' => 'required|integer|between:1,4',
            'learning_new_skills' => 'required|integer|between:1,4',

            // Quality of work
            'accuracy' => 'required|integer|between:1,4',
            'consistency' => 'required|integer|between:1,4',
            'follow_up' => 'required|integer|between:1,4',

            // Handling target and deadlines
            'completion_of_work_on_time' => 'required|integer|between:1,4',

            // Communication Skills
            'share_information/knowledge' => 'required|integer|between:1,4',
            'willingly' => 'required|integer|between:1,4',
            'reporting' => 'required|integer|between:1,4',

            // Interpersonal Skills
            'relationship_with_colleagues' => 'required|integer|between:1,4',
            'cooperation' => 'required|integer|between:1,4',
            'coordination' => 'required|integer|between:1,4',
            'team_work' => 'required|integer|between:1,4',
            'punctuality_attendance' => 'required|integer|between:1,4',
            'problem_solving' => 'required|integer|between:1,4',

            // Willingness to learn
            'willingness_to_learn' => 'required|integer|between:1,4',
            'open_to_ideas' => 'required|integer|between:1,4',
            'seeks_training' => 'required|integer|between:1,4',

            // Text fields
            'employees_achievements' => 'nullable|string',
            'performance_and_progress' => 'required|string',
            'new_goals_to_achieve' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => 'The :attribute field is required.',
            '*.between' => 'The :attribute must be between :min and :max.',
            '*.exists' => 'The selected :attribute is invalid.',
        ];
    }
}
