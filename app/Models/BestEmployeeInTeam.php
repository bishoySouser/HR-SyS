<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BestEmployeeInTeam extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'best_employee_in_team';
    protected $fillable = [
        'manager_id',
        'employee_id',
        'vote_date'
    ];

    public static function availableVoteDate()
    {
        // Get the current date
        $currentDate = new \DateTime();

        // Get the day of the current month
        $day = (int) $currentDate->format('d');

        // Get the last day of the current month
        $lastDay = (int) $currentDate->format('t');

        // Check if the current day is between the 25th and the last day of the month
        return $day >= 25 && $day <= $lastDay;
    }

    public static function voted()
    {
        $manager = Auth::user();
        $currentDate = Carbon::now()->startOfMonth();

        return BestEmployeeInTeam::where('manager_id', $manager->id)
                ->whereYear('vote_date', $currentDate->year)
                ->whereMonth('vote_date', $currentDate->month)
                ->exists();

    }
}
