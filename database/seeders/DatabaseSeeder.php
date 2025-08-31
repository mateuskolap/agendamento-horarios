<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class
        ]);

        Student::factory(20)->create();

        $admin = User::firstOrCreate([
            'name' => 'admin',
            'email' => 'admin@unicv.edu.br',
            'password' => Hash::make('admin@123'),
        ]);

        $admin->assignRole('admin');
    }
}
