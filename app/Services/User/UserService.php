<?php

namespace App\Services\User;

use App\Data\User\StoreUserData;
use App\Data\User\UpdateUserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(StoreUserData $data): User
    {
        $attributes = $data->toArray();
        if (!empty($data->password)) {
            $attributes['password'] = Hash::make($data->password);
        }

        return User::create($attributes)->assignRole($data->role_ids);
    }

    public function update(User $user, UpdateUserData $data): User
    {
        if (!empty($data->password)) {
            $attributes = $data->toArray();
            $attributes['password'] = Hash::make($data->password);
            $attributes['must_change_password'] = true;
        } else {
            $attributes = $data->except('password')->toArray();
        }

        $user->update($attributes);
        return $user->syncRoles($data->role_ids)->refresh();
    }
}

