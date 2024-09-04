<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EmployeeResource;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * get profile
     */
    public function getProfile()
    {
        $employee = Auth::user();

        return new EmployeeResource($employee);
    }

}
