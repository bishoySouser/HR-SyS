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

    protected $searchableRelations = [
        'employee' => ['full_name'],
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

    /**
     * Get the employee through the balance relationship.
     */
    public function employee()
    {
        return $this->hasOneThrough(
            Employee::class,
            VacationBalance::class,
            'id', // Foreign key on VacationBalance table...
            'id', // Foreign key on Employee table...
            'balance_id', // Local key on Vacation table...
            'employee_id' // Local key on VacationBalance table...
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, $search)
    {
        return $query->where(function($query) use ($search) {
            // Search in vacation attributes
            foreach ($this->searchableAttributes as $attribute) {
                $query->orWhere($attribute, 'LIKE', "%{$search}%");
            }

            // Search in related employee
            $query->orWhereHas('employee', function($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%");
            });
        });
    }

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
