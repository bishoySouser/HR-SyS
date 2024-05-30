<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EnrollmentRequest;
use App\Http\Resources\V1\CourseCollection;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CourseCollection(Course::all());
    }

    /**
     * Apply a course
     */
    public function applyCourse(EnrollmentRequest $request)
    {
        // Your logic to handle the request, e.g., create an enrollment
        $enrollment = Enrollment::create([
            'course_id' => $request->input('courseId'),
            'employee_id' => Auth::id(),
            'status' => 'pending', // or any other default status
        ]);

        return response()->json(['message' => 'Enrollment successful', 'enrollment' => $enrollment], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnrollmentRequest $request)
    {
        // Your logic to handle the request, e.g., create an enrollment
        $enrollment = Enrollment::create([
            'course_id' => $request->input('courseId'),
            'employee_id' => Auth::id(),
            'status' => 'pending', // or any other default status
        ]);

        return response()->json(['message' => 'Enrollment successful', 'enrollment' => $enrollment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
