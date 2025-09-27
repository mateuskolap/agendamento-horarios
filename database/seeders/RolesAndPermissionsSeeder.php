<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'courses.index',
            'courses.create',
            'courses.update',
            'courses.delete',
            'users.index',
            'users.create',
            'users.update',
            'users.delete',
        ];

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'admin'])
            ->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'coordinator']);

        Role::firstOrCreate(['name' => 'student']);
    }
}
