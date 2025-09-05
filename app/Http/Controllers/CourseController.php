<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::ofUser()->get();

        return Inertia::render('courses/Index', [
            'courses' => $courses,
        ]);
    }

    public function store(CourseRequest $request)
    {
        Course::create($request->validated());

        return redirect()->back();
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->back();
    }
}
