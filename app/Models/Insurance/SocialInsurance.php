<?php

namespace App\Models\Insurance;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class SocialInsurance extends Model
{
    use HasFactory, CrudTrait;

    protected $table = 'social_insurance';
    protected $guarded = ['id'];

    // Relations
    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function activateEmployees()
    {
        $this->status = 1;
    }

    public function openGoogle($crud = false)
{
    return '<a class="btn btn-sm btn-link" target="_blank" href="http://google.com?q='.urlencode($this->text).'" data-toggle="tooltip" title="Just a demo custom button."><i class="la la-search"></i> Google it</a>';
}

    public function deactivateEmployees()
    {
        $this->status = 0;
    }




}
