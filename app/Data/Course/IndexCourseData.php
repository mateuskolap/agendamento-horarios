<?php

namespace App\Data\Course;

use App\Models\Organization;
use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class IndexCourseData extends Data
{
    public function __construct(
        #[Exists(Organization::class, 'id')]
        public ?int $organization_id,

        public ?string $name,

        #[Exists(User::class, 'id')]
        public ?int $coordinator_id,
    ) {}
}
