<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
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
        try {
            $managerId = auth()->id();

            // Retrieve manager, employee, and department models
            $manager = Employee::find($managerId);
            $employee = Employee::find($request->input('employee_id'));

            // If any of the required models are missing, return an error
            if (!$manager || !$employee) {
                return response()->json(['message' => 'Invalid manager, employee, or department'], 400);
            }

            // Set the manager, employee, department, and vote date in the service
            $this->bestEmployeeService
                ->setManager($manager)
                ->setEmployee($employee);

            // Cast the vote
            $result = $this->bestEmployeeService->voteForBestEmployee();

            return response()->json([
                'status' => true,
                'status_code' => 201,
                'message' => 'Vote recorded successfully',
                'data' => $result
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => $e->getMessage(),
            ], 422);
        }

    }
}
