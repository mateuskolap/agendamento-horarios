<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
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
    public function index(IndexUserRequest $request): Response
    {
        $validated = $request->validated();

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

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role_ids']);

        event(new Registered($user));

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

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
