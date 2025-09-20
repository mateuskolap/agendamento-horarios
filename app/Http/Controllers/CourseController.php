<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\Course;
use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses_list = Course::query()
            ->select(['id', 'name', 'organization_id', 'coordinator_id'])
            ->with(['coordinator:id,user_id', 'coordinator.user:id,name', 'organization:id,name'])
            ->orderBy('courses.name')
            ->paginate(25);

        $coordinators_list = Coordinator::with('user:id,name')->get();
        $organizations_list = Organization::all();

        return Inertia::render('courses/Courses', [
            'courses_list' => $courses_list,
            'coordinators_list' => $coordinators_list,
            'organizations_list' => $organizations_list,
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
            'name' => ['nullable', 'string'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'coordinator_id' => ['nullable', 'exists:coordinators,id']
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
