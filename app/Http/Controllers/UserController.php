<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\Professor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $users_list = User::query()
            ->when(!empty($validated['name']), function ($query) use ($validated) {
                $query->where('users.name', 'like', '%' . $validated['name'] . '%');
            })
            ->when(!empty($validated['role_id']), function ($query) use ($validated) {
                $query->whereHas('roles', function ($query) use ($validated) {
                    $query->where('roles.id', $validated['role_id']);
                });
            })
            ->with('roles:id,name')
            ->paginate(10)
            ->withQueryString();

        $roles_list = Role::select('id', 'name')->get();

        return Inertia::render('users/Users', [
            'users_list' => $users_list,
            'roles_list' => $roles_list,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $role = Role::findById($validated['role_id']);
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

    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->delete();

        return redirect()->back()->with('success', 'Usuário exluído com sucesso');
    }
}
