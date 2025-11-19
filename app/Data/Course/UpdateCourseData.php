<?php

namespace App\Data\Course;

use App\Models\Organization;
use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class UpdateCourseData extends Data
{
    public function __construct(
        public ?string $name,

        #[Exists(Organization::class, 'id')]
        public ?int $organization_id,

        #[Exists(User::class, 'id')]
        public ?int $coordinator_id,
    ) {}
}
