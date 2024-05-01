<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Employee extends Authenticatable
{
    use CrudTrait, HasFactory, Notifiable, HasApiTokens;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
                        'full_name',
                        'email',
                        'phone_number',
                        'national_id',
                        'birthday',
                        'location',
                        'contract_periods',
                        'hire_date',
                        'grades',
                        'top_management',
                        'job_id',
                        'salary',
                        'manager_id',
                        'department_id',
                        'updated_at',
                        'created_at'
                    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function openVacations($crud = false)
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="'. url('admin/vacations') .'" data-toggle="tooltip" title="Just a demo custom button."><i class="
        la la-space-shuttle"></i> Vacations</a>';
    }

    // public function resetPassword($crud = false)
    // {
    //     $employee_id = $this->id;
    //     return view('your_view', compact('employee_id'));
    //     // return '<a class="btn btn-sm btn-link" href="'.route('resetPassword', $this->id).'" data-toggle="tooltip" title="Reset password."><i class="la la-unlock-alt"></i> Reset password </a>';
    // }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function manager() {
        return $this->belongsTo(Employee::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
