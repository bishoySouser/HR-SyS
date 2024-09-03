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
        return $this->hasMany(Employee::class, 'manager_id')->count() > 0;
    }

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

    public function itTickets() {
        return $this->hasMany(ItTicket::class);
    }

    public function excuses() {
        return $this->hasMany(Excuse::class);
    }

    public function workFromHomes() {
        return $this->hasMany(WorkFromHome::class);
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
        return $obj->max_in_month - $this->hasMany(WorkFromHome::class)->currentMonth()->count();
    }
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
