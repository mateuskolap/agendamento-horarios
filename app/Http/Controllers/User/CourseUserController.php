<?php

namespace App\Http\Controllers\User;

use App\Data\CourseUser\DestroyCourseUserData;
use App\Data\CourseUser\IndexCourseUserData;
use App\Data\CourseUser\StoreCourseUserData;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use App\Services\CourseUser\CourseUserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class CourseUserController extends Controller
{
    public function __construct(
        private readonly CourseUserService $courseUserService,
    )
    {
    }

    public function index(IndexCourseUserData $data, User $user): Response
    {
        $userCourses = collect();
        if ($data->role_id) {
            $selectedRole = Role::find($data->role_id);
            $userCourses = $user->courses()
                ->wherePivot('role_id', $data->role_id)
                ->with('organization:id,name')
                ->get();

            if ($userCourses->isEmpty() && $selectedRole->name === RoleEnum::COORDINATOR->value) {
                $userCourses = $user->coordinatedCourses()->with('organization')->get();
            }
        }

        $organizationsList = Organization::select('organizations.id', 'organizations.name')
            ->with(['courses' => function ($query) use ($userCourses) {
                $query->whereNotIn('courses.id', $userCourses->pluck('id'));
            }])
            ->get();

        return Inertia::render('users/courses/Index', [
            'user' => $user,
            'roles_list' => $user->roles,
            'user_courses' => $userCourses,
            'organizations_list' => $organizationsList,
        ]);
    }

    public function store(StoreCourseUserData $data, User $user): RedirectResponse
    {
        try {
            $this->courseUserService->create($data, $user);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Curso(s) vinculado(s) com sucesso!');
    }

    public function destroy(DestroyCourseUserData $data, User $user, Course $course): RedirectResponse
    {
        $user->courses()->wherePivot('role_id', $data->role_id)->detach($course->id);

        return back()->with('success', 'Curso removido com sucesso!');
    }
}
