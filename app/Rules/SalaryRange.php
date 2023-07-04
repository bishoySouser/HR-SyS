<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use App\Models\Job;

class SalaryRange implements ValidationRule
{
    protected $job_id;
    
    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $job = Job::find($this->job_id);

        if ($value < $job->min_salary || $value > $job->max_salary) {
            $fail("The :attribute must be range between $job->min_salary and $job->max_salary.");
        }
    }
}
