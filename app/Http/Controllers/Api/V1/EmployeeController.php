<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
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
        // Get the authenticated employee
        $employee = auth()->user();
        return Auth::user();
        // Ensure employee is not null
        if (!$employee) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Return the employee profile as a resource
        return new EmployeeResource($employee);
    }

    /**
     * get profile
     */
    public function getProfile()
    {
        // Get the authenticated employee
        $employee = Auth::user();


        // Return the employee profile as a resource
        return new EmployeeResource($employee);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
