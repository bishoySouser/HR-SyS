<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EmployeeOfTheMonthCollection;
use App\Models\EmployeeOfTheMonth;
use Illuminate\Http\Request;

class EmployeeOfTheMonthController extends Controller
{
    public function index()
    {
        $latestEmployeeOfMonth = EmployeeOfTheMonth::latest('month')
                                    ->paginate(10);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Vacations list of employee',
                'data' => new EmployeeOfTheMonthCollection($latestEmployeeOfMonth)
            ],200);

    }
}
