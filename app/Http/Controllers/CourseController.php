<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'name' => ['nullable', 'string'],
            'coordinator_id' => ['nullable', 'exists:users,id'],
        ]);

        $coursesList = Course::query()
            ->when(! empty($validated['organization_id']), function ($query) use ($validated) {
                $query->whereOrganizationId($validated['organization_id']);
            })
            ->when(! empty($validated['name']), function ($query) use ($validated) {
                $query->where('name', 'like', '%'.$validated['name'].'%');
            })
            ->when(! empty($validated['coordinator_id']), function ($query) use ($validated) {
                $query->whereCoordinatorId($validated['coordinator_id']);
            })
            ->select(['id', 'name', 'organization_id', 'coordinator_id'])
            ->with(['coordinator:id,name', 'organization:id,name'])
            ->orderBy('courses.id')
            ->paginate(10)
            ->withQueryString();

        $coordinatorsList = User::role('coordinator')->select('id', 'name')->get();
        $organizationsList = Organization::select('id', 'name')->get();

        return Inertia::render('courses/Index', [
            'courses_list' => $coursesList,
            'coordinators_list' => $coordinatorsList,
            'organizations_list' => $organizationsList,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:users,id'],
        ]);

        Course::create($validated);

        return back()->with('success', 'Curso criado com sucesso!');
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:users,id'],
        ]);

        $course->update($validated);

        return back()->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return back()->with('success', 'Curso excluído com sucesso!');
    }
}
