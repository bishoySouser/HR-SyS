<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vacation extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'vacations';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];

    protected $fillable = [
        'balance_id',
        'start_date',
        'end_date',
        'duration',
        'status',
        'reason_rejected',
    ];
    // protected $fillable = [];
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
    public function balance() {
        return $this->belongsTo(VacationBalance::class);
    }

    public function balanceEmployeeOfCurrentYear() {
        return $this->belongsTo(VacationBalance::class);
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    /**
     * Prepare a start_date for array / mass assignment.
     *
     * @param  string  $value
     * @return string|null
     */
    protected function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Prepare a end_date for array / mass assignment.
     *
     * @param  string  $value
     * @return string|null
     */
    protected function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
