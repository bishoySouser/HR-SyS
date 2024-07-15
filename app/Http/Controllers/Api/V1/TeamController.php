<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Excuse;
use App\Models\Vacation;
use App\Models\WorkFromHome;
use App\Services\WorkFromHomeLimitHandler;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function updateStatus(Request $request)
    {
        $response = [
            'status' => true,
            'status_code' => 422,
            'message' => 'Update',
            'data' => []
        ];

        return response()->json($response, 422);
    }

}
