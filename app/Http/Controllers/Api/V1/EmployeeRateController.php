<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\BestEmployeeInTeamService;
use Illuminate\Http\Request;

class EmployeeRateController extends Controller
{
    private $bestEmployeeService;

    public function __construct(BestEmployeeInTeamService $bestEmployeeService)
    {
        $this->bestEmployeeService = $bestEmployeeService;
    }

    public function managerRate()
    {
        return 'managers rate...';
    }

    public function voteEmployeeOfTeam(Request $request)
    {
        $managerId = auth()->id();

        $result = $this->bestEmployeeService->voteForBestEmployee(
            $managerId,
            $request->input('employee_id'),
            $request->input('department_id'),
            $request->input('vote_date')
        );

        if ($result) {
            return response()->json(['message' => 'Vote recorded successfully']);
        } else {
            return response()->json(['message' => 'Vote failed'], 400);
        }
    }
}
