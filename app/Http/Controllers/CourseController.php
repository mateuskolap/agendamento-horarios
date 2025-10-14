<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\IndexCourseRequest;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(IndexCourseRequest $request): Response
    {
        $validated = $request->validated();

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

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Course::create($validated);

        return back()->with('success', 'Curso criado com sucesso!');
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
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
