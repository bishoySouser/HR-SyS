<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BestManagerInCompany;
use App\Models\Department;
use App\Models\Employee;
use App\Services\BestEmployeeInTeamService;
use App\Services\BestManagerInCompanyService;
use Illuminate\Http\Request;

class EmployeeRateController extends Controller
{
    private $bestEmployeeService;
    private $bestManagerService;

    public function __construct(
        BestEmployeeInTeamService $bestEmployeeService,
        BestManagerInCompanyService $bestManagerService
    ){
        $this->bestEmployeeService = $bestEmployeeService;
        $this->bestManagerService = $bestManagerService;
    }

    public function managerRate(Request $request)
    {
        try {
            $employeeId = auth()->id();

            // Retrieve manager, employee, and department models
            $employee = Employee::find($employeeId);
            $manager = Employee::find($request->input('manager_id'));

            // If any of the required models are missing, return an error
            if (!$manager || !$employee) {
                return response()->json(['message' => 'Invalid manager, employee'], 400);
            }

            // Set the manager, employee, department, and vote date in the service
            $this->bestManagerService
                ->setEmployee($employee)
                ->setManager($manager)
                ->setReason($request->input('reson'));


            // Cast the vote
            $result = $this->bestManagerService->voteForBestManager();

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

    public function voteEmployeeOfTeam(Request $request)
    {
        try {
            $managerId = auth()->id();

            // Retrieve manager, employee, and department models
            $manager = Employee::find($managerId);
            $employee = Employee::find($request->input('employee_id'));

            // If any of the required models are missing, return an error
            if (!$manager || !$employee) {
                return response()->json(['message' => 'Invalid manager, employee'], 400);
            }

            // Set the manager, employee, department, and vote date in the service
            $this->bestEmployeeService
                ->setManager($manager)
                ->setEmployee($employee)
                ->setReason($request->input('reson'));

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
