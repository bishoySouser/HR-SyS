<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CurrentOrFutureYearRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentYear = now()->year;

        if ($value < $currentYear) {
            $fail("The {$attribute} must be the current year or a future year.");
        }
    }
}
