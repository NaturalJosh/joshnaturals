<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return response()->json([
            'success' => true,
            'data'    => Student::all(),
        ]);
    }

    // POST /api/students
    public function store(Request $request)
{
    $validated = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname'  => 'required|string|max:255',
        'course'    => 'required|string|max:255',
        'year'      => 'required|integer|min:1',
    ]);

    $student = Student::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Student created successfully',
        'data'    => $student,
    ], 201);
}

    // GET /api/students/{student}
    public function show(Student $student)
    {
        return response()->json([
            'success' => true,
            'data'    => $student,
        ]);
    }

    // PUT/PATCH /api/students/{student}
    public function update(Request $request, Student $student)
{
    $validated = $request->validate([
        'firstname' => 'sometimes|required|string|max:255',
        'lastname'  => 'sometimes|required|string|max:255',
        'course'    => 'sometimes|required|string|max:255',
        'year'      => 'sometimes|required|integer|min:1',
    ]);

    $student->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Student updated successfully',
        'data'    => $student,
    ]);
}

    // DELETE /api/students/{student}
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully',
        ]);
    }
}