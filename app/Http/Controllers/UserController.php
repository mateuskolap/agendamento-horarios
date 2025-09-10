<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Coordinator;
use App\Models\Professor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $user = User::paginate(25);

        return Inertia::render('users/Index', [
            'courses' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:users,email'],
            'role' =>['required', 'string', Rule::enum(UserRole::class)]
         ]);

        $user = User::create($validated);
        switch ($validated['role']) {
            case 'Professor':
                Professor::create($user->id);
            break;

        case 'Pluno':
                Student::create($user->id);
            break;

        case 'coordenador':
                Coordinator::create($user->id);
            break;
        }

        return redirect()->back()->with('success', 'Usuário criado com sucesso!');
    }

    public function destroy(User $course)
    {
        $course->delete();

        return redirect()->back()->with('success', 'Usuário excluido com sucesso!');
    }


}
