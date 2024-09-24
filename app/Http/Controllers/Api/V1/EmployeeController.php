<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\BestManagerInCompany;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class EmployeeController extends Controller
{
    public function getEmployeeTree()
    {
        $employees = Employee::where(function (Builder $query) {
                        $query->whereNull('deleted_at')
                            ->orWhere(function (Builder $query) {
                                $query->whereNotNull('deleted_at')
                                    ->whereHas('subordinates');
                            });
                    })
                    ->withTrashed()
                    ->get();

        $tree = [];

        foreach ($employees as $employee) {

            $isTrashed = $employee->trashed();

            $node = [
                'id' => $employee->id,
                'parentId' => $employee->manager_id,
                'fullName' => $isTrashed ? 'missed' : $employee->full_name,
                'jobTitle' => $employee->job->title,
                'email' =>  $isTrashed ? 'missed' : $employee->email,
                'phone' =>  $isTrashed ? 'missed' : $employee->phone_number,
                'image' =>  $isTrashed ? 'missed' : $employee->profile_pic,
                'department' => $employee->department->name,
                // 'isDeleted' => ,
            ];

            $tree[] = $node;
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Team list',
            'data' => $tree
        ],200);

    }

    /**
     * get profile
     */
    public function getProfile()
    {
        $employee = Auth::user();

        return new EmployeeResource($employee);
    }

    public function getManagersWithoutTheirOwnManager()
    {
        $employee = Auth::user();
        // check managers rated in current team
        if (!BestManagerInCompany::availableVoteDate()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Voting is not available at this time.',
            ], 422);
        } elseif (BestManagerInCompany::voted()) {
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'You have already voted for this month',
            ], 422);
        }

        $managers = $employee->getAllManagersWithoutTheirManagers();

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Managers list without their own manager',
            'data' => $managers
        ],200);
    }

}
