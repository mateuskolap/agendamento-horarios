<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\Course;
use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $validated = $request->validate([
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'name' => ['nullable', 'string'],
            'coordinator_id' => ['nullable', 'exists:coordinators,id'],
        ]);

        $courses_list = Course::query()
            ->when(!empty($validated['organization_id']), function ($query) use ($validated) {
                $query->where('organization_id', $validated['organization_id']);
            })
            ->when(!empty($validated['name']), function ($query) use ($validated) {
                $query->where('name', 'like', '%' . $validated['name'] . '%');
            })
            ->when(!empty($validated['coordinator_id']), function ($query) use ($validated) {
                $query->where('coordinator_id', $validated['coordinator_id']);
            })
            ->select(['id', 'name', 'organization_id', 'coordinator_id'])
            ->with(['coordinator.user:id,name', 'organization:id,name'])
            ->orderBy('courses.id')
            ->paginate(10)
            ->withQueryString();

        $coordinators_list = Coordinator::with('user:id,name')->get();
        $organizations_list = Organization::select('id','name')->get();

        return Inertia::render('courses/Courses', [
            'courses_list' => $courses_list,
            'coordinators_list' => $coordinators_list,
            'organizations_list' => $organizations_list,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ]);

        Course::create($validated);

        return redirect()->back()->with('success', 'Curso criado com sucesso!');
    }

    public function update(Request $request, Course $course): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ]);

        $course->update($validated);

        return redirect()->back()->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy(Course $course): \Illuminate\Http\RedirectResponse
    {
        $course->delete();

        return redirect()->back()->with('success', 'Curso excluído com sucesso!');
    }
}
