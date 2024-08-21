<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreExcuseRequest;
use App\Http\Resources\V1\ExcuseCollection;
use App\Http\Resources\V1\ExcuseResource;
use App\Models\Excuse;
use App\Services\ExcuseLimitService;
use App\Utils\TimeConverter;
use Illuminate\Support\Facades\Auth;

class ExcuseController extends Controller
{
    protected $excuseLimitService;

    public function __construct(ExcuseLimitService $excuseLimitService)
    {
        $this->excuseLimitService = $excuseLimitService;
    }

    public function index()
    {
        $user = auth()->user();

        $excuses = $user->excuses()
                    ->with('employee')
                    ->latest()
                    ->paginate(10);

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Excuses list of employee',
            'data' => new ExcuseCollection($excuses)
        ], 200);
    }

    public function store(StoreExcuseRequest $request)
    {
        $employee = auth()->user();
        $excuseTimeSeconds = TimeConverter::convertTimeToSeconds($request->input('time'));

        $requestData = [
            'employee_id' => $employee->id,
            'date' => $request->date,
            'duration' => $excuseTimeSeconds
        ];

        [$canRequest, $message] = $this->excuseLimitService->canRequest($requestData);

        if (!$canRequest) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => $message,
                'data' => []
            ], 422);
        }
        // print_r($request->all());
        $data_to_create = [
            ...$request->all(),
            'employee_id' => $employee->id,
            'status' => 'Pending'
        ];

        $excuse = Excuse::create($data_to_create);

        return response()->json([
            'status' => true,
            'status_code' => 201,
            'message' => 'Excuse request submitted successfully',
            'data' => new ExcuseResource($excuse)
        ], 201);
    }
}
