<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWorkFromHomeRequest;
use App\Http\Resources\V1\WorkFromHomeCollection;
use App\Http\Resources\V1\WorkFromHomeResource;
use App\Models\Employee;
use App\Models\WorkFromHome;
use App\Services\WorkFromHomeService;
use App\Services\WorkFromHomeLimitHandler;
use Carbon\Carbon;

class WorkFromHomeController extends Controller
{
    protected $workFromHomeService;

    public function __construct(WorkFromHomeService $workFromHomeService)
    {
        $this->workFromHomeService = $workFromHomeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $list = $user->workFromHomes()
                    ->with('employee')
                    ->latest()
                    ->paginate(10);

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Work from home list of employee',
            'data' => new WorkFromHomeCollection($list)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkFromHomeRequest $request)
    {
        $employee = Employee::find(auth()->user()->id);

        // Check if the employee can request work from home
        $requestData = [
            'employee_id' => $employee->id,
            'day' => $request->input('day')
        ];

        [$canRequest, $message] = $this->workFromHomeService->canRequest($requestData);

        if (!$canRequest) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => $message,
                'data' => []
            ], 422);
        }

        $data_to_create = [
            ...$request->all(),
            'employee_id' => $employee->id,
            'status' => 'Pending'
        ];

        $workFromHome = WorkFromHome::create($data_to_create);

        return response()->json([
            'status' => true,
            'status_code' => 201,
            'message' => 'Work from home request submitted successfully',
            'data' => new WorkFromHomeResource($workFromHome)
        ], 201);
    }
}
