<?php

namespace App\Models;

use App\Models\Insurance\SocialInsurance;
use App\Models\Insurance\MedicalInsurance;
use App\Services\WorkFromHomeLimitHandler;
use App\Services\WorkFromHomeService;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Employee extends Authenticatable
{
    use CrudTrait, HasFactory, Notifiable, HasApiTokens, SoftDeletes;

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
                        'profile_pic',
                        'phone_number',
                        'national_id',
                        'birthday',
                        'location',
                        'eduction',
                        'contract_periods',
                        'hire_date',
                        'grades',
                        'top_management',
                        'job_id',
                        'salary',
                        'manager_id',
                        'department_id',
                        'updated_at',
                        'created_at',
                        'deleted_at',
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

    /**
     * Return the HTML for the upload attach button.
     *
     * @return string
     */
    public function uploadAttachButton(Object|bool $crud = false)
    {
        $id = $crud->getCurrentEntryId();
        return '<a href="' . backpack_url("employee/{$id}/upload-attach") . '" class="btn btn-sm btn-link"><i class="la la-upload"></i> Upload Attachment</a>';
    }

    public function isManager()
    {
        $hasSubordinates = $this->hasMany(Employee::class, 'manager_id')->count() > 0;
        $isManagerGrade =  $this->job->grades === 'manager' ? true : false;

        return $hasSubordinates || $isManagerGrade;
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /**
     * get all managers without CEO and thier owner manager
     */
    public function getAllManagersWithoutTheirManagers()
    {
        $managers = self::select('id', 'full_name as Name')
                        ->whereHas('job', function($query) {
                            $query->where('grades', 'manager');
                        })
                        ->where('id', '!=', $this->manager_id)
                        ->whereNotNull('manager_id')
                        ->orderBy('name', 'ASC')
                        ->get();

        return $managers;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
     /**
     * get list evaluation for employee don't evaluate this year
     */
    public function getAvaliableEvaluation(): array
    {
        $allEvaluations = [
            'quarter_1',
            'quarter_2',
            'quarter_3',
            'quarter_4',
            'end_of_probation',
            'end_of_year'
        ];

        $completedEvaluations = $this->evaluationsAsEmployee()
            ->where('year', date('Y'))
            ->pluck('evaluation_type')
            ->toArray();

        return [
            'completed' => $completedEvaluations,
            'pending' => array_values(array_diff( $allEvaluations, $completedEvaluations))
        ];
    }

    public function evaluationsAsEmployee()
    {
        return $this->hasMany(EmployeeEvaluation::class, 'employee_id');
    }

    public function evaluationsAsEvaluator()
    {
        return $this->hasMany(EmployeeEvaluation::class, 'evaluator_id');
    }

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


    public function itTickets() {
        return $this->hasMany(ItTicket::class);
    }

    public function excuses() {
        return $this->hasMany(Excuse::class);
    }

    public function workFromHomes() {
        return $this->hasMany(WorkFromHome::class);
    }

    public function employeeOfTheMonth()
    {
        return $this->hasMany(EmployeeOfTheMonth::class);
    }

    /**
     * Get the social insurance associated with the employee.
     */
    public function socialInsurance()
    {
        return $this->hasOne(SocialInsurance::class)->where('status', 1);
    }

    /**
     * Get the medical insurance associated with the employee.
     */
    public function medicalInsurance()
    {
        return $this->hasOne(MedicalInsurance::class)->where('status', 1);
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
    public function getFnameAttribute()
    {
        // Split the full name into an array of parts
        $parts = explode(' ', $this->full_name);

        // Return the first part (first name)
        return $parts[0] ?? null;
    }

    /**
     * get first name and second name
     */
    public function getUsernameAttribute()
    {
        // Split the full name into an array of parts
        $parts = explode(' ', $this->full_name);

        // Return the first part (first name)
        return $parts[0] . " " . $parts[1] ?? null;
    }

    public function getFirstAndSecondNameAttribute()
    {
        $fullName = $this->full_name; // Assuming full_name is the field

        // Split the full name by spaces
        $names = explode(' ', $fullName);

        // Check if the full name has at least two parts
        if (count($names) >= 3) {
            // Return the first and second names
            return $names[0] . ' ' . $names[2];
        }

        // Return the full name if it has less than two parts
        return $fullName;
    }

    public function getJobTitleAttribute()
    {
        return $this->job->grades . ' ' . $this->job->title;
    }

      /**
     * Get the URL of the product image.
     *
     * @return string|null
     */
    public function getProfilePicAttribute($value)
    {
        if (isset($value) && Storage::disk('public')->exists($value)) {
            return asset('storage/' . $value);
        }

        return 'not found';
    }

    public function getRequestCountForCurrentMonth()
    {
        $obj = new WorkFromHomeService();
        return $obj->getLimitInMonth() - $this->hasMany(WorkFromHome::class)->currentMonth()->count();
    }

    // public function employeeOfTheMonth()
    // {
    //     return $this->hasMany(EmployeeOfTheMonth::class);
    // }

    // // Method to calculate how many times an employee was chosen
    // public function getTimesSelected()
    // {
    //     return $this->employeeOfTheMonth()->count();
    // }

    // // Method to calculate selection rate based on hire date and number of months
    // public function getSelectionRate()
    // {
    //     $hireDate = $this->hire_date;
    //     $monthsWorked = now()->diffInMonths($hireDate);

    //     $timesSelected = $this->getTimesSelected();
    //     return ($monthsWorked > 0) ? ($timesSelected / $monthsWorked) * 100 : 0;
    // }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */


    /**
     * @param  string  $value
     * @return void
     */
    public function setProfilePicAttribute($value)
    {

        $this->attributes['profile_pic'] = $value ? Storage::disk('public')->put('Employees', $value) : null; // Adjust disk if needed
    }
}
