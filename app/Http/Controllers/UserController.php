<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\Professor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $request->validate([
            'name' => ['nullable', 'string'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $users_list = User::query()
            ->when(!empty($request['name']), function ($query) use ($request) {
                $query->where('users.name', 'like', '%' . $request['name'] . '%');
            })
            ->when(!empty($request['role_id']), function ($query) use ($request) {
                $query->whereHas('roles', function ($query) use ($request) {
                    $query->where('roles.id', $request['role_id']);
                });
            })
            ->with('roles:id,name')
            ->paginate(15)
            ->withQueryString();

        $roles_list = Role::select('id', 'name')->get();

        return Inertia::render('users/Users', [
            'users_list' => $users_list,
            'roles_list' => $roles_list,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::findById($request['role_id']);
        $user->assignRole($role->name);

        switch ($role->name) {
            case 'student':
                Student::create(['user_id' => $user->id]);
                break;

            case 'coordinator':
                Coordinator::create(['user_id' => $user->id,]);
                break;

            case 'professor':
                Professor::create(['user_id' => $user->id,]);
                break;
        }

        event(new Registered($user));

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Usuário exluído com sucesso');
    }
}
