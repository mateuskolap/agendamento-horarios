<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AvailabilityController extends Controller
{
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $availabilitiesList = Availability::query()
            ->when(! empty($validated['user_id']), function ($query) use ($validated) {
                $query->where('user_id', $validated['user_id']);
            })
            ->when(! empty($validated['role_id']), function ($query) use ($validated) {
                $query->where('role_id', $validated['role_id']);
            })
            ->paginate(10)
            ->withQueryString();

        $usersList = User::whereHas('availabilities')->get();
        $rolesList = Availability::with('role')->get()->map->role;

        return Inertia::render('availabilities/Index', [
            'availabilities_list' => $availabilitiesList,
            'users_list' => $usersList,
            'roles_list' => $rolesList,
        ]);
    }
}
