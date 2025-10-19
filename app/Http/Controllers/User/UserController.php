<?php

namespace App\Http\Controllers\User;

use App\Data\User\StoreUserData;
use App\Data\User\UpdateUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
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
        $storeUserData = StoreUserData::from($request->validated());

        $user = $this->userService->create($storeUserData);
        event(new Registered($user));

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $updateUserData = UpdateUserData::from($request->validated());

        $this->userService->update($user, $updateUserData);

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->back()->with('success', 'Usuário exluído com sucesso');
    }
}
