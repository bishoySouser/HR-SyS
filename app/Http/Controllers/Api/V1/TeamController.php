<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateTeamRequest;
use App\Http\Resources\V1\ExcuseResource;
use App\Http\Resources\V1\VacationResource;
use App\Http\Resources\V1\WorkFromHomeResource;
use App\Jobs\V1\UpdateExcuseRequest;
use App\Jobs\V1\UpdateTimeOffRequest;
use App\Jobs\V1\UpdateWorkFromHomeRequest;
use App\Jobs\V1\UpdateWorkFromRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Excuse;
use App\Models\Vacation;
use App\Models\WorkFromHome;
use App\Services\WorkFromHomeLimitHandler;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $manager = auth()->user(); // Assuming the logged-in user is the manager

        // Get all employees under this manager
        $employeeIds = Employee::where('manager_id', $manager->id)->pluck('id');

        $excuses = Excuse::whereIn('employee_id', $employeeIds)
            ->where('status', 'Pending')
            ->with('employee')
            ->get();

        $workFromHome = WorkFromHome::whereIn('employee_id', $employeeIds)
            ->where('status', 'Pending')
            ->with('employee')
            ->get();

        $vacations = Vacation::whereHas('balance', function ($query) use ($employeeIds) {
            $query->whereIn('employee_id', $employeeIds);
        })->where('status', 'pending')
        ->with('balance.employee')
        ->get();

        $formattedRequests = [];

        foreach ($excuses as $excuse) {

            $formattedRequests[] = [
                'id' => $excuse->id,
                'empID' => $excuse->employee->id,
                'name' => $excuse->employee->full_name,
                'type' => 'EXCUSE',
                'date' => Carbon::parse($excuse->date)->format('D , F d,Y'),
                'details' => $excuse->type == 'early_leave' ? "Early Leave $excuse->time Hours" : "Late Arrival $excuse->time Hours",
                'remaining' => "Excuses: $excuse->remainingsExcuse Remaining",
            ];
        }

        foreach ($workFromHome as $wfh) {

            $formattedRequests[] = [
                'id' => $wfh->id,
                'empID' => $wfh->employee->id,
                'name' => $wfh->employee->full_name,
                'type' => 'WORK FROM HOME',
                'date' => Carbon::parse($wfh->day)->format('D , F d,Y'),
                'remaining' => "Work From Home Requests: " . $wfh->employee->getRequestCountForCurrentMonth(),
            ];
        }

        foreach ($vacations as $vacation) {
            $formattedRequests[] = [
                'id' => $vacation->id,
                'empID' => $vacation->balance->employee->id,
                'name' => $vacation->balance->employee->full_name,
                'type' => 'TIME OFF',
                'from' => Carbon::parse($vacation->start_date)->format('D , F d,Y'),
                'to' => Carbon::parse($vacation->end_date)->format('D , F d,Y'),
                'remaining' => "Vacation Requests: " . $vacation->balance->remaining_days,
            ];
        }

        return response()->json($formattedRequests);
    }

    public function updateTeamRequest(UpdateTeamRequest $request)
    {
        $manager = auth()->user();


        $response = [
            'status' => true,
            'status_code' => 422,
            'message' => 'Update',
            'data' => []
        ];

        try {
            $requestData = null;

            switch ($request->type) {
                case 'WORK FROM HOME':
                    UpdateWorkFromHomeRequest::dispatch($request->id, $request->action, $manager->id);
                    $requestData = new WorkFromHomeResource( WorkFromHome::findOrFail($request->id) );
                    break;
                case 'EXCUSE':
                    UpdateExcuseRequest::dispatch($request->id, $request->action, $manager->id);
                    $requestData = new ExcuseResource( Excuse::findOrFail($request->id) );
                    break;
                case 'TIME OFF':
                    UpdateTimeOffRequest::dispatch($request->id, $request->action, $manager->id);
                    $requestData = new VacationResource( Vacation::findOrFail($request->id) );
                    break;
                default:
                    return response()->json(['error' => 'Invalid request type'], 400);
            }

            return response()->json([
                'status' => true,
                'status_code' => 201,
                'message' => "Your request is being processed.",
                'data' => $requestData
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }

    public function getEmployeesOfTeam()
    {
        $manager = Auth::user();

        $employees = Employee::select('full_name as Name')->where('manager_id', $manager->id)->get();

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'employees list of department',
            'data' => $employees,
        ],200);
    }

}
