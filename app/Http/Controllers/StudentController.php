<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        return Inertia::render('students/Index', [
            'students' => $students
        ]);
    }

    public function store(StudentRequest $request)
    {
        Student::create($request->validated());

        return redirect()->back();
    }

    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->back();
    }
}
