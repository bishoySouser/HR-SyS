<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreExcuseRequest;
use App\Http\Resources\V1\ExcuseCollection;
use App\Models\Excuse;
use App\Services\ExcuseLimitService;
use App\Utils\TimeConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExcuseController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // More concise way to get authenticated user

        $excuses = $user->excuses()
                    ->with('employee') // Eager load related employee data
                    ->latest() // Order by latest creation date
                    ->paginate(10); // Paginate results for efficiency

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Excuses list of employee',
            'data' => new ExcuseCollection($excuses)
        ],200);
    }

    public function store(StoreExcuseRequest $request)
    {
        $employee_id = Auth::id();
        $excuseTimeSeconds = TimeConverter::convertTimeToSeconds($request->input('time'));

        $excuseLimitService = new ExcuseLimitService($employee_id, now()->month, now()->year);

        if ($excuseLimitService->exceedsMonthlyLimit($excuseTimeSeconds)) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Excuse limit exceeded for this month. You have ' . $excuseLimitService->remainingSeconds() . ' seconds remaining',
                'data' => []
            ], 422);
        }

        $data_to_create = [...$request->all(), 'employee_id' => $employee_id, 'status' => 'Acknowledge'];

        $excuse = Excuse::create($data_to_create);

        return response()->json([
            'status' => true,
            'status_code' => 201,
            'message' => 'Excuse successful',
            'data' => $excuse
        ], 201);

    }
}
