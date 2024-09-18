<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestManagerInCompany extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'best_manager_in_company';
    protected $fillable = [
        'employee_id',
        'manager_id',
        'vote_date'
    ];
}
