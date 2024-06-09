<?php

namespace App\Utils;

class TimeConverter
{
    /**
     * Convert the time in the format "HH:mm" seconds
     *
     * @param string $time
     * @return int
     */
    public static function convertTimeToSeconds($time)
    {
        $parts = explode(':', $time);
        return ($parts[0] * 3600) + ($parts[1] * 60);
    }
}
