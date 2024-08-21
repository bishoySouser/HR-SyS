<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class TimeRange implements ValidationRule
{
    protected $minSeconds;
    protected $maxSeconds;

    public function __construct()
    {
        // 1 minute = 60 seconds, 2 hours = 7200 seconds
        $this->minSeconds = 60;     // 1 minute
        $this->maxSeconds = 7200;   // 2 hours
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Convert the time (H:i or H:i:s) into seconds
        $timeParts = explode(':', $value);

        if (count($timeParts) < 2 || count($timeParts) > 3) {
            $fail("The {$attribute} must be in a valid format (H:i or H:i:s).");
            return;
        }

        // Handle both H:i and H:i:s formats
        $hours = (int) $timeParts[0];
        $minutes = (int) $timeParts[1];
        $seconds = count($timeParts) === 3 ? (int) $timeParts[2] : 0;

        // Calculate total seconds
        $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

        // Check if the total seconds fall within the range
        if ($totalSeconds < $this->minSeconds || $totalSeconds > $this->maxSeconds) {
            $fail("The {$attribute} must be between 1 minute and 2 hours.");
        }
    }
}
