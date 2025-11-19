<?php

namespace App\Http\Controllers\User;

use App\Data\User\IndexUserData;
use App\Data\User\StoreUserData;
use App\Data\User\UpdateUserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    public function index(IndexUserData $data): Response
    {
        $usersList = User::query()
            ->when($data->name, function ($query) use ($data) {
                $query->where('users.name', 'like', '%'.$data->name.'%');
            })
            ->when($data->role_id, function ($query) use ($data) {
                $query->whereHas('roles', function ($query) use ($data) {
                    $query->where('roles.id', $data->role_id);
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

    public function store(StoreUserData $data): RedirectResponse
    {
        $user = $this->userService->create($data);
        event(new Registered($user));

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso');
    }

    public function update(UpdateUserData $data, User $user): RedirectResponse
    {
        $this->userService->update($user, $data);

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->back()->with('success', 'Usuário exluído com sucesso');
    }
}
