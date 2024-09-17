<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestEmployeeInTeam extends Model
{
    use HasFactory;
    protected $table = 'best_employee_in_team';
    protected $fillable = [
        'manager_id',
        'employee_id',
        'vote_date'
    ];
}
