<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function getEmployeeTree()
    {
        $employees = Employee::all();

        $tree = [];

        foreach ($employees as $employee) {
            $node = [
                'id' => $employee->id,
                'parentId' => $employee->manager_id ?: null, // Assuming you have a manager_id field
                'full_name' => $employee->full_name,
                'job_title' => $employee->job_title,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'image' => $employee->profile_pic,
                'department' => $employee->department->name,
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
