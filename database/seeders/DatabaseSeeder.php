<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
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
            AdminUserSeeder::class
        ]);

        if (app()->environment('local')) {
            $students = Student::factory(20)->create();
            $courses = Course::factory(10)->create();

            $students->each(function ($student) use ($courses) {
                $student->courses()->attach(
                    $courses->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
        }
    }
}
