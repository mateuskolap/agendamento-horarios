<?php

namespace App\Http\Controllers\User;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseUser\IndexCourseUserRequest;
use App\Http\Requests\DestroyCourseUserRequest;
use App\Http\Requests\StoreCourseUserRequest;
use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class CourseUserController extends Controller
{
    public function index(IndexCourseUserRequest $request, User $user): Response
    {
        $validated = $request->validated();

        $userCourses = collect();
        if (!empty($validated['role_id'])) {
            $selectedRole = Role::find($validated['role_id']);
            $userCourses = $user->courses()
                ->wherePivot('role_id', $validated['role_id'])
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

    public function store(StoreCourseUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $role = Role::find($validated['role_id']);

        if (!$role->courses_editable) {
            return back()->with('error', 'Não é permitida a alteração dos cursos para usuários com a função: ' . $role->name . '.');
        }

        foreach ($validated['course_ids'] as $courseId) {
            if ($user->courses()->wherePivot('role_id', $validated['role_id'])->where('courses.id', $courseId)->exists()) {
                continue; // Esse curso já está vinculado ao usuário
            }

            $user->courses()->attach($courseId, ['role_id' => $validated['role_id']]);

        }

        return back()->with('success', 'Curso(s) vinculado(s) com sucesso!');
    }

    public function destroy(DestroyCourseUserRequest $request, User $user, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $user->courses()->wherePivot('role_id', $validated['role_id'])->detach($course->id);

        return back()->with('success', 'Curso removido com sucesso!');
    }
}
