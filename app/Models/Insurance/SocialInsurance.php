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

}
