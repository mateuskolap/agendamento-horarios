<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'role' => ['nullable', Rule::in(Role::pluck('name')->toArray())],
        ]);

        $users_list = User::query()
            ->when(!empty($validated['name']), function ($query) use ($validated) {
                $query->where('users.name', 'like', '%' . $validated['name'] . '%');
            })
            ->when(!empty($validated['role']), function ($query) use ($validated) {
                $query->whereHas('roles', function ($query) use ($validated) {
                    $query->where('roles.name', $validated['role']);
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

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Usuário exluído com sucesso');
    }
}
