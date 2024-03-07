<?php

namespace App\Models\Insurance;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class MedicalInsurance extends Model
{
    use HasFactory, CrudTrait;

    protected $table = 'medical_insurance';
    protected $guarded = ['id'];

    // Relations
    public function employee() {
        return $this->belongsTo(Employee::class);
    }

}
