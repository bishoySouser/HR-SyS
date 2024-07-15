<?php

namespace App\Models;

use App\Services\ExcuseLimitService;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Excuse extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'excuses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'employee_id',
        'time',
        'type',
        'reason',
        'status',
        'date',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeForTimeInMonthBySecound($query, $employeeId, $month, $year)
    {
        return $query->where('employee_id', $employeeId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum(\DB::raw('TIME_TO_SEC(time)'));
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    // /**
    //  * Prepare a date for array / mass assignment.
    //  *
    //  * @param  string  $value
    //  * @return string|null
    //  */
    // protected function setDateAttribute($value)
    // {
    //     $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    // }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    protected function getRemainingsExcuseAttribute()
    {
        $excuseLimitService = new ExcuseLimitService($this->employee_id, now()->month, now()->year);

        $seconds = $excuseLimitService->remainingSeconds();
        $carbon = Carbon::createFromTimestamp($seconds);
        return $carbon->diffInHours(Carbon::createFromTimestamp(0));
    }
}
