<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class CourseUserController extends Controller
{
    public function index(Request $request, User $user): Response
    {
        $validated = $request->validate([
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $courses = collect();
        $selectedRole = null;

        if (! empty($validated['role_id'])) {
            $selectedRole = Role::find($validated['role_id']);
            $courses = $user->courses()
                ->wherePivot('role_id', $validated['role_id'])
                ->with('organization:id,name')
                ->get();
        }

        return Inertia::render('users/courses/Index', [
            'user' => $user,
            'roles_list' => $user->roles,
            'courses_list' => $courses,
            'selected_role' => $selectedRole,
        ]);
    }

    public function destroy(User $user, Course $course): RedirectResponse {
        $user->courses()->detach($course->id);

        return back()->with('success','Curso removido com sucesso!');
    }
}
