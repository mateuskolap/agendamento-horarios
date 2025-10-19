<?php

namespace App\Data\CourseUser;

use Spatie\LaravelData\Data;

class StoreCourseUserData extends Data
{
    public function __construct(
        public int $role_id,
        /** @var array<int> */
        public array $course_ids,
    ) {}
}
