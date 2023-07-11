<?php

namespace App\Repositories\Employee;

use App\Repositories\BaseRepository;
use App\Repositories\Employee\EmployeeInterface;

class EmployeeRepository extends BaseRepository implements EmployeeInterface
{
    public function getModel()
    {
        return \App\Models\Employee::class;
    }
}
