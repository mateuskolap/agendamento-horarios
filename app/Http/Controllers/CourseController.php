<?php

namespace App\Http\Controllers;

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
}
