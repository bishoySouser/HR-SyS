<?php

namespace App\Http\Requests;

use App\Models\EmployeeOfTheMonth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class EmployeeOfTheMonthRequest extends FormRequest
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
        $employeeId = $this->route('id') ?? $this->input('employee_id');
        $month = $this->input('month');

        return [
            'employee_id' => 'required|exists:employes,id',
            'month' => [
                'required',
                'date_format:Y-m',
                Rule::unique('employee_of_the_month')
                    ->where(function ($query) use ($employeeId, $month) {
                        $query->where('employee_id', $employeeId)
                            ->whereMonth('month', date('m', strtotime($month)))
                            ->whereYear('month', date('Y', strtotime($month)));
                    })
                    ->ignore($this->route('id')),
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
            'employee_id.required' => 'The employee field is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'month.required' => 'The month field is required.',
            'month.date_format' => 'The month must be in the format YYYY-MM.',
            'month.unique' => 'The employee has already been selected for the given month.',
        ];
    }

}
