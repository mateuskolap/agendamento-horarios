<?php

namespace Database\Seeders;

use App\Enums\OrganizationEnum;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (OrganizationEnum::cases() as $org) {
            Organization::firstOrCreate(['name' => $org]);
        }
    }
}
