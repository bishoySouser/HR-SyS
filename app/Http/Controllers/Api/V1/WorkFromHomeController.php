<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWorkFromHomeRequest;
use App\Models\Employee;
use App\Models\WorkFromHome;
use App\Services\WorkFromHomeLimitHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkFromHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkFromHomeRequest $request)
    {
        $employee = Employee::find(auth()->user()->id);
        $limitHandler = new WorkFromHomeLimitHandler();

        // Check if the employee has already submitted 2 work from home requests for the current month
        $currentMonth = Carbon::now()->month;
        $workFromHomeCountThisMonth = $employee->workFromHomes()
            ->whereMonth('day', $currentMonth)
            ->count();

        // Check if the employee has reached the work from home limit
        if ($limitHandler->hasReachedLimit($employee)) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'You have already submitted the maximum number of work from home requests for this month.',
                'data' => []
            ], 422);
        }

        $data_to_create = [...$request->all(), 'employee_id' => $employee->id, 'status' => 'Acknowledge'];

        $workFromHome = WorkFromHome::create($data_to_create);

        return response()->json([
            'status' => true,
            'status_code' => 201,
            'message' => 'Work from home request submitted successfully',
            'data' => $workFromHome
        ], 201);

    }
}
