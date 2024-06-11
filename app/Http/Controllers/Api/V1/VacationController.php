<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreVacationRequest;
use App\Http\Resources\V1\VacationResource;
use App\Models\Vacation;
use App\Services\VacationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacationRequest $request)
    {

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
