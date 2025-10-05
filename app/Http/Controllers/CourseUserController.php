<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseUserController extends Controller
{
    public function index(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $courses = collect();

        if (! empty($validated['role_id'])) {
            $courses = $user->courses()
                ->wherePivot('role_id', $validated['role_id'])
                ->get();
        }

        return Inertia::render('users/courses/Index', [
            'user' => $user,
            'roles' => $user->roles,
            'courses' => $courses,
        ]);
    }
}
