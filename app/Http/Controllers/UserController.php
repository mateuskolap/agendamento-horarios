<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $usersList = User::query()
            ->when(! empty($validated['name']), function ($query) use ($validated) {
                $query->where('users.name', 'like', '%'.$validated['name'].'%');
            })
            ->when(! empty($validated['role_id']), function ($query) use ($validated) {
                $query->whereHas('roles', function ($query) use ($validated) {
                    $query->where('roles.id', $validated['role_id']);
                });
            })
            ->with('roles:id,name')
            ->paginate(10)
            ->withQueryString();

        $rolesList = Role::select('id', 'name')->get();

        return Inertia::render('users/Index', [
            'users_list' => $usersList,
            'roles_list' => $rolesList,
        ]);
    }

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('users.courses.index', ['user' => $user]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_ids' => ['required', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role_ids']);

        event(new Registered($user));

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
            $user->must_change_password = true;
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        $user->syncRoles($validated['role_ids']);

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->back()->with('success', 'Usuário exluído com sucesso');
    }
}
