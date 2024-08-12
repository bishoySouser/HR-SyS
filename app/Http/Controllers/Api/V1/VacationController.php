<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreVacationRequest;
use App\Http\Resources\V1\VacationCollection;
use App\Http\Resources\V1\VacationResource;
use App\Models\Vacation;
use App\Services\VacationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class VacationController extends Controller
{
    protected $vacationService;

    public function __construct(VacationService $vacationService)
    {
        $this->vacationService = $vacationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $vacations = Vacation::with('balance')
                        ->whereHas('balance', function ($query) use ($user) {
                            $query->where('employee_id', $user->id);
                        })
                        ->latest(); // Order by the latest vacations first

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Vacations list of employee',
            'data' => new VacationCollection($vacations->paginate(10))
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacationRequest $request)
    {
        $user = Auth::user();

        if ($this->vacationService->hasPendingVacation($user->id)) {
            return response()->json([
                'status' => false,
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'You already have a pending vacation request.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $balance_id = $this->vacationService->getBalanceIdForCurrentYear();

        $data_to_create = [...$request->all(), 'balance_id' => $balance_id];

        $vacation = Vacation::create($data_to_create);

        return response()->json([
            'status' => true,
            'status_code' => 201,
            'message' => 'Vacation request submitted successfully',
            'data' => new VacationResource($vacation)
        ], 201);
    }

}
