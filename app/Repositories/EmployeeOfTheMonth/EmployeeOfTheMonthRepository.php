<?php

namespace App\Repositories\EmployeeOfTheMonth;

use App\Repositories\BaseRepository;
use App\Repositories\Employee\EmployeeInterface;

class EmployeeOfTheMonthRepository extends BaseRepository implements EmployeeOfTheMonthInterface
{
    public function getModel()
    {
        return \App\Models\EmployeeOfTheMonth::class;
    }


}
