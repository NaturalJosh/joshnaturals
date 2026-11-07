<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Student::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:students,email',
        ]);

        $student = Student::create($data);

        return response()->json([
            'data'    => $student,
            'message' => 'Student added.',
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json(['data' => $student]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:students,email,' . $student->id,
        ]);

        $student->update($data);

        return response()->json([
            'data'    => $student,
            'message' => 'Student updated.',
        ]);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json(['message' => 'Student deleted.']);
    }
}