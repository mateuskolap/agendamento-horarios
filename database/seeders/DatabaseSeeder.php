<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Coordinator;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            OrganizationSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Para desenvolvimento local, cria dados fictícios
        if (app()->environment('local', 'development')) {
            Coordinator::factory()->count(10)->create();
        }
    }
}
