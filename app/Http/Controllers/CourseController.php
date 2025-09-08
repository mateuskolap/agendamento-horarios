<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(25);

        return Inertia::render('courses/Index', [
            'courses' => $courses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ]);

        Course::create($validated);

        return redirect()->back()->with('success', 'Curso criado com sucesso!');
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ]);

        $course->update($validated);

        return redirect()->back()->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->back()->with('success', 'Curso excluído com sucesso!');
    }
}
