<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BestManagerInCompany extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'best_manager_in_company';
    protected $fillable = [
        'employee_id',
        'manager_id',
        'vote_date'
    ];

    public static function availableVoteDate()
    {
        // Get the current date
        $currentDate = new \DateTime();
        $from = (int) Setting::get('revaluation_employee_from');
        $to = (int) Setting::get('revaluation_employee_to');

        // Get the day of the current month
        $day = (int) $currentDate->format('d');

        // Check if the current day is between the 25th and the 29th of the month
        return $day >= $from && $day <= $to;
    }

    public static function voted()
    {
        $employee = Auth::user();
        $currentDate = Carbon::now()->startOfMonth();

        return self::where('employee_id', $employee->id)
                ->whereYear('vote_date', $currentDate->year)
                ->whereMonth('vote_date', $currentDate->month)
                ->exists();

    }
}
