<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\Course;
use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'name' => ['nullable', 'string'],
            'coordinator_id' => ['nullable', 'exists:coordinators,id'],
        ]);

        $courses_list = Course::query()
            ->when(!empty($request['organization_id']), function ($query) use ($request) {
                $query->where('organization_id', $request['organization_id']);
            })
            ->when(!empty($request['name']), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request['name'] . '%');
            })
            ->when(!empty($request['coordinator_id']), function ($query) use ($request) {
                $query->where('coordinator_id', $request['coordinator_id']);
            })
            ->select(['id', 'name', 'organization_id', 'coordinator_id'])
            ->with(['coordinator.user:id,name', 'organization:id,name'])
            ->orderBy('courses.id')
            ->paginate(15)
            ->withQueryString();

        $coordinators_list = Coordinator::with('user:id,name')->get();
        $organizations_list = Organization::select('id','name')->get();

        return Inertia::render('courses/Courses', [
            'courses_list' => $courses_list,
            'coordinators_list' => $coordinators_list,
            'organizations_list' => $organizations_list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ]);

        Course::create($request);

        return redirect()->back()->with('success', 'Curso criado com sucesso!');
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:coordinators,id']
        ]);

        $course->update($request);

        return redirect()->back()->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->back()->with('success', 'Curso excluído com sucesso!');
    }
}
