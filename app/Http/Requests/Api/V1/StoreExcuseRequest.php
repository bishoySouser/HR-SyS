<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\TimeRange;


class StoreExcuseRequest extends FormRequest
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
            'time' => ['required', 'date_format:H:i', new TimeRange()],
            'type' => 'required|in:early_leave,late_arrival',
            'reason' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
        ];
    }
}
