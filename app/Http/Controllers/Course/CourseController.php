<?php

namespace App\Http\Controllers\Course;

use App\Data\Course\GetAvailableCoursesByRequestedData;
use App\Data\Course\IndexCourseData;
use App\Data\Course\StoreCourseData;
use App\Data\Course\UpdateCourseData;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(IndexCourseData $data): Response
    {
        $coursesList = Course::query()
            ->when($data->organization_id, function ($query) use ($data) {
                $query->whereOrganizationId($data->organization_id);
            })
            ->when($data->name, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data->name . '%');
            })
            ->when($data->coordinator_id, function ($query) use ($data) {
                $query->whereCoordinatorId($data->coordinator_id);
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

    public function getAvailableByRequested(GetAvailableCoursesByRequestedData $data): Collection
    {
        $user = \Auth::user();
        $requested = User::find($data->requested_id);

        if ($requested->roles()->where('can_receive_scheduling_requests', true)->doesntExist()) {
            return collect();
        }

        return $user->courses()
            ->select(['courses.id', 'courses.name', 'organization_id'])
            ->availableByRequested($requested)
            ->with('organization:id,name')
            ->get()
            ->makeHidden('pivot');
    }

    public function store(StoreCourseData $data): RedirectResponse
    {
        Course::create($data->toArray());

        return back()->with('success', 'Curso criado com sucesso!');
    }

    public function update(UpdateCourseData $data, Course $course): RedirectResponse
    {
        $course->update($data->toArray());

        return back()->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return back()->with('success', 'Curso excluído com sucesso!');
    }
}
