<?php

namespace Database\Factories;

use App\Models\Coordinator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CoordinatorFactory extends Factory
{
    protected $model = Coordinator::class;

    public function definition(): array
    {
        $user = User::factory()->create()->assignRole('coordinator');

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => $user->id,
        ];
    }
}
