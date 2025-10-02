<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class CourseUserController extends Controller
{
    public function index(User $user) {
        // Carrega os cursos com join para incluir dados da role
        $user->load(['courses' => function ($query) {
            $query->join('roles', 'course_user.role_id', '=', 'roles.id')
                  ->select('courses.*', 'roles.name as role_name', 'course_user.role_id as pivot_role_id')
                  ->withPivot('role_id');
        }]);

        return Inertia::render('users/show/Courses', props: [
            'user' => $user,
        ]);
    }
}
